<div>
    <div class="flex items-center gap-4 mb-6">
        <a href="/admin/machines" class="neumorphic-btn w-10 h-10 rounded-2xl flex items-center justify-center text-slate-500 hover:text-slate-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Manajemen Slot</h2>
            <h1 class="text-xl font-bold text-slate-800">{{ $machine->name }}</h1>
        </div>
        <button wire:click="openModal" class="ml-auto neumorphic-btn px-4 py-2 rounded-xl text-xs font-bold text-emerald-600 flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Tambah Slot
        </button>
    </div>

    <div class="space-y-4">
        @forelse($slots as $slot)
        <div class="neumorphic-flat p-4 rounded-3xl flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl neumorphic-inset flex items-center justify-center text-lg font-bold text-slate-600">
                    {{ $slot->slot_number }}
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">{{ $slot->product->name ?? 'Kosong (Tidak ada produk)' }}</h3>
                    <p class="text-[10px] font-bold text-emerald-600">Harga Jual: Rp {{ number_format($slot->price, 0, ',', '.') }}</p>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-500">Stok: <span class="{{ $slot->stock <= 2 ? 'text-red-500' : 'text-slate-800' }}">{{ $slot->stock }}</span> / {{ $slot->capacity }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-wider {{ $slot->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                            {{ $slot->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <button wire:click="edit({{ $slot->id }})" class="neumorphic-btn w-8 h-8 rounded-lg flex items-center justify-center text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                </button>
                <button wire:click="delete({{ $slot->id }})" wire:confirm="Yakin ingin menghapus slot ini?" class="neumorphic-btn w-8 h-8 rounded-lg flex items-center justify-center text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-xs font-medium text-slate-400">Belum ada slot dikonfigurasi untuk mesin ini</div>
        @endforelse
    </div>

    <!-- Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-[#e0e5ec] p-6 rounded-[2rem] shadow-[20px_20px_60px_#bec3c9,-20px_-20px_60px_#ffffff] w-full max-w-md max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold text-slate-700 mb-4">{{ $isEditMode ? 'Edit Slot & Restok' : 'Tambah Slot' }}</h3>
            
            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Pilih Produk</label>
                    <select wire:model="product_id" class="w-full bg-[#e0e5ec] border-none rounded-xl px-4 py-2 text-sm shadow-[inset_5px_5px_10px_#bec3c9,inset_-5px_-5px_10px_#ffffff] focus:ring-emerald-500 focus:outline-none">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->name }} (Harga Dasar: Rp {{ number_format($prod->base_price, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                    @error('product_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Nomor Slot</label>
                        <input type="text" wire:model="slot_number" class="w-full bg-[#e0e5ec] border-none rounded-xl px-4 py-2 text-sm shadow-[inset_5px_5px_10px_#bec3c9,inset_-5px_-5px_10px_#ffffff] focus:ring-emerald-500 focus:outline-none" placeholder="Misal: A1">
                        @error('slot_number') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Harga Jual (Rp)</label>
                        <input type="number" wire:model="price" class="w-full bg-[#e0e5ec] border-none rounded-xl px-4 py-2 text-sm shadow-[inset_5px_5px_10px_#bec3c9,inset_-5px_-5px_10px_#ffffff] focus:ring-emerald-500 focus:outline-none" placeholder="0">
                        @error('price') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Stok Saat Ini</label>
                        <input type="number" wire:model="stock" class="w-full bg-[#e0e5ec] border-none rounded-xl px-4 py-2 text-sm shadow-[inset_5px_5px_10px_#bec3c9,inset_-5px_-5px_10px_#ffffff] focus:ring-emerald-500 focus:outline-none" placeholder="0">
                        @error('stock') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Kapasitas Maks.</label>
                        <input type="number" wire:model="capacity" class="w-full bg-[#e0e5ec] border-none rounded-xl px-4 py-2 text-sm shadow-[inset_5px_5px_10px_#bec3c9,inset_-5px_-5px_10px_#ffffff] focus:ring-emerald-500 focus:outline-none" placeholder="10">
                        @error('capacity') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" wire:model="is_active" id="is_active" class="rounded text-emerald-500 focus:ring-emerald-500">
                    <label for="is_active" class="text-xs font-bold text-slate-500">Slot Aktif (Tampil di mesin)</label>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="closeModal" class="flex-1 neumorphic-btn py-2 rounded-xl text-sm font-bold text-slate-500">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 neumorphic-btn py-2 rounded-xl text-sm font-bold text-emerald-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
