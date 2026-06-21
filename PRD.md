# Product Requirements Document (PRD)
## Sistem Vending Machine Gantungan Kunci Berbasis Laravel + Midtrans + ESP32

**Versi:** 1.0
**Tanggal:** 21 Juni 2026
**Status:** Draft

---

## 1. Latar Belakang & Tujuan

Membangun sistem vending machine digital untuk penjualan gantungan kunci secara otomatis dan mandiri (self-service). Pembeli memindai QR Code pada mesin fisik, memilih produk melalui antarmuka web, melakukan pembayaran digital via Midtrans, dan menerima barang secara otomatis melalui mekanisme motor putar pada mesin.

**Tujuan Utama:**
- Memungkinkan transaksi tanpa kasir (cashless & unmanned).
- Mendukung banyak unit vending machine yang dikelola dari satu sistem backend terpusat.
- Memastikan stok dan status dispense (pengeluaran barang) akurat, tanpa duplikasi maupun barang gagal keluar yang tidak terdeteksi.

---

## 2. Ruang Lingkup (Scope)

### 2.1 Termasuk dalam Scope
- Aplikasi web pembeli (katalog produk per mesin, keranjang, checkout)
- Integrasi pembayaran Midtrans (Snap)
- API komunikasi antara backend Laravel dan firmware ESP32 di tiap mesin
- Manajemen multi-mesin, multi-slot per mesin
- Manajemen stok otomatis per slot
- Dashboard admin (manajemen produk, mesin, slot, monitoring transaksi)
- Mekanisme deteksi keberhasilan dispense (anti-gagal, anti-dobel)

### 2.2 Di Luar Scope (Versi 1.0)
- Aplikasi mobile native (cukup web responsif)
- Sistem refund otomatis (refund dilakukan manual oleh admin)
- Multi metode pembayaran selain Midtrans
- Sistem loyalty/membership pembeli
- Real-time push communication (MQTT) — direncanakan untuk versi berikutnya

---

## 3. Pengguna & Aktor Sistem

| Aktor | Deskripsi |
|---|---|
| **Pembeli (Customer)** | Memindai QR, memilih produk, membayar, menerima barang. Tidak perlu login/akun. |
| **Admin** | Mengelola data mesin, slot, produk, stok, memantau transaksi, dan menangani transaksi gagal. |
| **Sistem Midtrans** | Pihak ketiga penyedia payment gateway, berkomunikasi via Snap Token & webhook. |
| **Firmware ESP32 (Mesin)** | Mengeksekusi perintah pemutaran motor dan melaporkan status ke backend. |

---

## 4. Alur Proses Utama (User Flow)

1. Pembeli memindai QR Code unik yang tertera di mesin vending tertentu.
2. QR Code mengarah ke URL halaman produk: `/machine/{machine_id}`.
3. Sistem menampilkan daftar slot yang tersedia di mesin tersebut, masing-masing menampilkan: nama produk, foto, harga, dan sisa stok per slot.
4. Pembeli menambahkan satu atau lebih slot ke keranjang beserta quantity (qty per slot dibatasi oleh stok slot tersebut).
5. Pembeli melanjutkan ke checkout; sistem membuat order dengan status `pending` dan meminta Snap Token dari Midtrans.
6. Popup pembayaran Midtrans Snap muncul; pembeli menyelesaikan pembayaran.
7. Midtrans mengirimkan notifikasi (webhook) ke backend Laravel mengenai status transaksi.
8. Backend memvalidasi notifikasi, mengubah status order menjadi `paid` jika sukses, dan menyiapkan instruksi dispense untuk tiap item order (per slot, per qty).
9. Firmware ESP32 di mesin melakukan polling berkala ke backend untuk mengecek apakah ada instruksi dispense baru.
10. Backend mengembalikan instruksi: slot mana saja yang harus diputar dan berapa kali.
11. ESP32 mengeksekusi pemutaran motor sesuai instruksi, dengan validasi sensor untuk memastikan barang benar-benar keluar.
12. ESP32 melaporkan status hasil dispense (sukses/gagal) per item ke backend.
13. Backend memperbarui stok slot, menandai item sebagai `dispensed`, dan menyelesaikan status order menjadi `completed` (atau menandai sebagai gagal sebagian untuk ditindaklanjuti admin).

---

## 5. Functional Requirements

### 5.1 Manajemen Produk & Mesin (Admin)
- FR-1: Admin dapat menambah, mengubah, menghapus data **produk master** (nama, deskripsi, foto, harga dasar).
- FR-2: Admin dapat menambah, mengubah, menghapus data **mesin** (nama mesin, lokasi, kode unik mesin untuk QR).
- FR-3: Admin dapat mengatur **slot** dalam suatu mesin: nomor slot, produk yang mengisi slot tersebut, kapasitas maksimal, dan stok saat ini.
- FR-4: Admin dapat mengubah harga jual per slot (memungkinkan harga berbeda untuk produk sama di mesin/slot berbeda jika diperlukan).
- FR-5: Admin dapat melihat status real-time tiap slot (stok, status aktif/nonaktif, status terakhir dispense).
- FR-6: Admin dapat menonaktifkan slot tertentu sementara (misal sedang kosong/rusak) tanpa menghapus datanya.

### 5.2 Tampilan Produk untuk Pembeli
- FR-7: Sistem menampilkan daftar slot yang berstatus aktif dan memiliki stok > 0 untuk mesin tertentu berdasarkan kode unik mesin di URL.
- FR-8: Setiap slot ditampilkan sebagai entitas terpisah (bukan digabung berdasarkan produk), lengkap dengan nama produk, foto, harga, dan sisa stok.
- FR-9: Pembeli dapat menambahkan slot ke keranjang dengan quantity, dibatasi maksimum sebesar stok slot tersebut saat itu.
- FR-10: Pembeli dapat mengubah/menghapus item dari keranjang sebelum checkout.
- FR-11: Sistem menghitung total harga secara otomatis di sisi client (untuk UX cepat) dan divalidasi ulang di server saat checkout.

### 5.3 Proses Pemesanan & Pembayaran
- FR-12: Sistem membuat record order dengan status `pending` saat pembeli melanjutkan ke checkout.
- FR-13: Sistem melakukan validasi ulang stok tiap slot di server sebelum membuat transaksi Midtrans (mencegah race condition jika 2 pembeli checkout slot sama bersamaan).
- FR-14: Sistem memanggil Midtrans Snap API untuk mendapatkan Snap Token, lalu menampilkan popup pembayaran di sisi client.
- FR-15: Sistem menerima dan memverifikasi notifikasi webhook dari Midtrans (termasuk validasi signature key untuk keamanan).
- FR-16: Status order diperbarui berdasarkan status transaksi Midtrans: `paid`, `failed`, `expired`, atau `cancelled`.
- FR-17: Order yang `pending` lebih dari durasi tertentu (misal 15 menit tanpa pembayaran) otomatis kedaluwarsa dan slot dilepas kembali (tidak mengunci stok selamanya).

### 5.4 Proses Dispense (Pengeluaran Barang)
- FR-18: Setelah order berstatus `paid`, sistem menyiapkan satu instruksi dispense per item order (per kombinasi slot dan qty).
- FR-19: Firmware ESP32 melakukan HTTP polling berkala ke endpoint backend untuk memeriksa instruksi dispense yang tertunda khusus untuk mesinnya.
- FR-20: Backend hanya mengirimkan instruksi untuk item dengan status `dispensed = false`.
- FR-21: Setelah instruksi dikirim ke ESP32, backend mencatat waktu pengiriman instruksi (`dispense_attempted_at`) untuk keperluan deteksi macet/stuck.
- FR-22: ESP32 mengeksekusi pemutaran motor pada slot sesuai instruksi, sejumlah qty yang diminta.
- FR-23: Sistem mendeteksi keberhasilan tiap putaran melalui sensor fisik (infrared/photo-interrupter) pada jalur keluar barang.
- FR-24: ESP32 melaporkan hasil dispense (sukses/gagal) per item kembali ke backend melalui endpoint konfirmasi.
- FR-25: Backend memperbarui stok slot dan status item (`dispensed = true`) hanya setelah menerima konfirmasi sukses dari ESP32, dilakukan dalam satu transaksi database untuk mencegah duplikasi maupun race condition.
- FR-26: Jika ESP32 tidak melaporkan hasil dalam batas waktu tertentu (misal 30 detik) setelah instruksi dikirim, order/item ditandai berstatus `stuck`/`dispense_failed` untuk ditindaklanjuti admin.
- FR-27: Sistem mencegah pengiriman instruksi dispense ganda untuk item yang sama (idempotency check berbasis status `dispensed`).

### 5.5 Manajemen & Monitoring Admin
- FR-28: Admin dapat melihat daftar seluruh order beserta statusnya (pending, paid, completed, failed, stuck).
- FR-29: Admin dapat melihat detail tiap order: item apa saja, dari slot mana, sudah dispense atau belum.
- FR-30: Admin mendapat notifikasi/penanda visual untuk order yang berstatus `stuck` atau `dispense_failed` agar dapat ditindaklanjuti (refund manual/cek fisik mesin).
- FR-31: Admin dapat melihat riwayat stok dan pergerakan stok per slot.
- FR-32: Admin dapat melihat status koneksi/keaktifan tiap mesin (kapan terakhir mesin melakukan polling).

---

## 5.6 Halaman Admin (Rincian Menu)

Dashboard admin diakses melalui web biasa (bukan mobile-first, karena dipakai dari kantor/komputer), menggunakan Laravel + Livewire untuk kebutuhan interaktivitas (filter, search, update data tanpa reload halaman penuh). Berikut rincian menu yang dibutuhkan:

**1. Dashboard / Ringkasan**
- Ringkasan total penjualan (hari ini/minggu ini/bulan ini)
- Jumlah transaksi sukses vs gagal
- Daftar order bermasalah (stuck/dispense_failed) yang butuh tindak lanjut segera
- Status koneksi mesin (online/offline berdasarkan waktu polling terakhir)

**2. Manajemen Produk**
- List, tambah, edit, hapus produk master (nama, deskripsi, foto)
- Produk ini bersifat reusable, bisa dipasang di banyak slot/mesin berbeda

**3. Manajemen Mesin**
- List, tambah, edit, hapus data mesin (nama, lokasi, kode unik untuk QR, API key autentikasi ESP32, status aktif/nonaktif)
- Generate/cetak ulang QR Code per mesin
- Lihat riwayat aktivitas polling per mesin (kapan terakhir online)

**4. Manajemen Slot**
- List slot per mesin (nomor slot, produk yang terpasang, harga jual, stok saat ini, kapasitas maksimal, status aktif)
- Update stok manual (misal saat admin mengisi ulang slot secara fisik)
- Aktifkan/nonaktifkan slot tertentu (misal sedang kosong atau motor rusak, tanpa harus hapus data)

**5. Transaksi (Orders)**
- List seluruh order dengan filter status (pending, paid, completed, failed, expired, stuck)
- Filter berdasarkan mesin, rentang tanggal, atau nominal
- Detail tiap order: data pembeli (jika ada), mesin asal, total harga, status pembayaran Midtrans, waktu transaksi

**6. Item Transaksi (Order Items)**
- Detail item per order: slot mana yang dibeli, qty, harga saat transaksi, status dispensed (sudah/belum keluar), waktu instruksi dikirim ke ESP32, waktu konfirmasi selesai
- Berguna untuk menelusuri kasus komplain pembeli ("saya sudah bayar tapi barang tidak keluar")

**7. Transaksi Bermasalah (Stuck/Dispense Failed)**
- Halaman khusus yang menyaring order/item dengan status `stuck` atau `dispense_failed`
- Admin dapat menandai tindak lanjut (misal: "sudah di-refund manual", "sudah dicek fisik mesin, ternyata barang nyangkut")
- Mempermudah proses audit dan penyelesaian komplain tanpa harus mencari manual di seluruh data order

**8. Log Dispense** *(opsional, untuk audit lebih dalam)*
- Riwayat tiap percobaan dispense per item: berhasil/gagal, pesan error dari ESP32 (jika ada), waktu kejadian
- Berguna untuk analisis kalau ada slot/motor tertentu yang sering bermasalah

**9. Manajemen User Admin** *(jika multi-admin)*
- Tambah/kelola akun admin lain dengan hak akses (misal: super admin vs admin operasional yang hanya bisa lihat data, tidak bisa hapus)

---

## 6. Non-Functional Requirements

| Kategori | Kebutuhan |
|---|---|
| **Performa** | Halaman katalog produk harus termuat di bawah 2 detik pada koneksi WiFi standar. Delay antara pembayaran sukses dan motor mulai berputar idealnya di bawah 5 detik (tergantung interval polling ESP32). |
| **Keamanan** | Webhook Midtrans wajib divalidasi menggunakan signature key resmi untuk mencegah pemalsuan notifikasi pembayaran. Endpoint API untuk ESP32 wajib menggunakan autentikasi (misal API key per mesin) agar tidak bisa diakses sembarang pihak. |
| **Reliabilitas** | Sistem harus tetap konsisten meskipun terjadi race condition (dua pembeli checkout bersamaan, polling ESP32 bersamaan, dsb) melalui database transaction & locking. |
| **Skalabilitas** | Arsitektur harus mendukung penambahan jumlah mesin dan jumlah slot per mesin tanpa perubahan struktur data. |
| **Auditability** | Setiap perubahan status order dan stok harus tercatat dengan timestamp untuk keperluan audit dan penyelesaian sengketa. |
| **Ketersediaan** | Backend harus dapat diakses melalui HTTPS dengan domain publik (wajib untuk menerima webhook Midtrans dan koneksi ESP32 dari luar jaringan lokal). |
| **Mobile-First UX** | Tampilan pembeli wajib dioptimalkan untuk layar HP terlebih dahulu (lebar ~360-430px), dengan navigasi bottom bar fixed agar mudah dijangkau satu tangan. Tampilan desktop/tablet menyesuaikan (graceful scaling), bukan prioritas utama desain. |

---

## 7. Struktur Data (High-Level Data Model)

| Entitas | Keterangan |
|---|---|
| `machines` | Data mesin vending: kode unik, nama, lokasi, status aktif, API key untuk autentikasi ESP32. |
| `products` | Master data produk: nama, deskripsi, foto. |
| `machine_slots` | Slot fisik dalam mesin: nomor slot, mesin terkait, produk terkait, harga, stok saat ini, kapasitas, status aktif. |
| `orders` | Transaksi: mesin terkait, status, total harga, ID transaksi Midtrans, waktu dibuat/kedaluwarsa. |
| `order_items` | Detail tiap item dalam order: slot terkait, qty, harga saat transaksi, status dispensed, waktu instruksi dikirim, waktu dispense selesai. |
| `dispense_logs` *(opsional, untuk audit)* | Catatan tiap percobaan dispense: berhasil/gagal, waktu, pesan error dari ESP32 jika ada. |

---

## 8. Arsitektur Teknis (Ringkasan)

- **Backend:** Laravel (PHP), dengan pemisahan `routes/web.php` (halaman pembeli) dan `routes/api.php` (komunikasi dengan JavaScript checkout, webhook Midtrans, dan komunikasi ESP32).
- **Frontend Pembeli:** Blade templating + Alpine.js untuk interaktivitas keranjang belanja ringan, serta integrasi Midtrans Snap.js untuk popup pembayaran. Styling menggunakan **Tailwind CSS**.
- **Pendekatan UI/UX:** **Mobile-first** — karena 100% pembeli mengakses dari HP (hasil scan QR), seluruh layout dirancang utama untuk layar kecil terlebih dahulu, baru menyesuaikan ke layar lebih besar (desktop dianggap sekunder).
- **Navigasi:** Menggunakan **bottom navigation bar** (fixed di bawah layar) menyerupai pola aplikasi mobile pada umumnya, agar terasa familiar dan mudah dijangkau ibu jari pengguna. Contoh menu: Beranda/Produk, Keranjang (dengan badge jumlah item), Riwayat/Status Pesanan.
- **Dashboard Admin:** Direncanakan menggunakan Livewire untuk kebutuhan interaktivitas manajemen data.
- **Payment Gateway:** Midtrans Snap (server-to-server token generation + client-side popup + webhook notification).
- **Komunikasi Backend ↔ Mesin:** HTTP polling dari ESP32 ke Laravel (request berkala untuk mengecek instruksi dispense, dan endpoint pelaporan hasil).
- **Hardware:** ESP32 sebagai kontroler utama tiap mesin, motor stepper per slot untuk presisi jumlah putaran, sensor infrared/photo-interrupter pada jalur keluar barang untuk validasi fisik keberhasilan dispense.

---

## 9. Skenario Kegagalan & Penanganan (Edge Cases)

| Skenario | Penanganan |
|---|---|
| Pembayaran sukses tapi motor gagal putar (macet) | Sensor tidak terpicu dalam batas waktu → ESP32 lapor gagal → order ditandai `dispense_failed` → admin tindak lanjut manual (refund/reset slot). |
| Dua pembeli checkout slot yang sama bersamaan, stok tinggal 1 | Validasi stok ulang di server saat create order (locking row), salah satu transaksi ditolak sebelum lanjut ke pembayaran. |
| ESP32 polling dua kali sebelum instruksi pertama selesai diproses | Backend hanya mengembalikan item dengan `dispensed = false`; setelah instruksi dikirim sekali, status diberi penanda agar tidak dikirim ulang sebelum ada laporan hasil atau timeout. |
| Pembeli sudah generate Snap Token tapi tidak menyelesaikan pembayaran | Order tetap `pending`, otomatis kedaluwarsa dan stok kembali tersedia setelah durasi tertentu. |
| Webhook Midtrans terkirim lebih dari sekali untuk transaksi yang sama | Backend melakukan pengecekan idempotency berdasarkan ID transaksi Midtrans sebelum memproses ulang. |
| Koneksi internet mesin terputus saat proses dispense berlangsung | Instruksi tetap tersimpan di backend; begitu ESP32 kembali online dan polling, instruksi yang belum selesai akan diproses kembali (dengan validasi agar tidak dobel jika sebagian sudah sempat tereksekusi). |

---

## 10. Metrik Keberhasilan (Success Metrics)

- Tingkat keberhasilan dispense otomatis (barang berhasil keluar tanpa intervensi admin) > 98%.
- Rata-rata waktu dari pembayaran sukses hingga barang keluar di bawah target yang ditentukan (misal < 10 detik).
- Tidak ada kasus pengurangan stok ganda atau dispense ganda dalam pengujian beban (load testing dengan transaksi bersamaan).
- Seluruh transaksi gagal/stuck dapat teridentifikasi otomatis di dashboard admin tanpa laporan manual dari pembeli.

---

## 11. Pertanyaan Terbuka / Untuk Diputuskan Lebih Lanjut

- Berapa lama batas waktu order `pending` dianggap kedaluwarsa?
- Berapa interval polling ideal untuk ESP32 (trade-off antara kecepatan respons vs beban server)?
- Apakah perlu mekanisme refund otomatis via Midtrans API untuk kasus `dispense_failed`, atau tetap manual oleh admin di versi awal?
- Apakah QR Code per mesin bersifat statis (1 kode tetap) atau dinamis (berubah berkala untuk keamanan)?
- Apakah dibutuhkan notifikasi (email/WhatsApp) ke admin secara real-time saat ada order `stuck`?

---

*Dokumen ini akan diperbarui seiring berjalannya proses desain teknis dan pengembangan.*