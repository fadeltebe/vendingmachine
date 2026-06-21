<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vending Machine Soft UI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E2E8F0; /* Soft gray background */
        }
        /* Efek Neumorphism Timbul */
        .neumorphic-inset {
            background: #E2E8F0;
            box-shadow: inset 4px 4px 8px #cbd5e1, inset -4px -4px 8px #ffffff;
        }
        .neumorphic-flat {
            background: #E2E8F0;
            box-shadow: 5px 5px 10px #cbd5e1, -5px -5px 10px #ffffff;
        }
        .neumorphic-btn {
            background: #E2E8F0;
            box-shadow: 3px 3px 6px #cbd5e1, -3px -3px 6px #ffffff;
        }
        .neumorphic-btn:active {
            box-shadow: inset 2px 2px 5px #cbd5e1, inset -2px -2px 5px #ffffff;
        }
    </style>
</head>

<body class="flex justify-center items-center min-h-screen text-slate-700 antialiased">

    <div class="w-full max-w-md min-h-screen bg-[#E2E8F0] flex flex-col justify-between relative shadow-2xl overflow-x-hidden pb-24">

        <header class="px-6 pt-6 pb-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 font-medium tracking-wide">Selamat Datang!</p>
                    <h1 class="text-xl font-bold text-slate-800">Smart Vending</h1>
                </div>
                <div class="neumorphic-flat px-3 py-1.5 rounded-full flex items-center gap-1.5 text-[10px] font-bold text-emerald-500 uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Connected
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-5">
                <div class="neumorphic-inset p-3 rounded-2xl text-center">
                    <span class="text-[10px] text-slate-400 block mb-0.5 font-semibold">SUHU MESIN</span>
                    <span class="text-base font-bold text-slate-800">24.5 °C</span>
                </div>
                <div class="neumorphic-inset p-3 rounded-2xl text-center">
                    <span class="text-[10px] text-slate-400 block mb-0.5 font-semibold">METODE BAYAR</span>
                    <span class="text-base font-bold text-emerald-600">QRIS</span>
                </div>
            </div>
        </header>

        <main class="px-6 flex-1">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Pilih Produk Anda</h2>
            <div class="grid grid-cols-2 gap-4">

                <div class="neumorphic-flat p-3.5 rounded-3xl flex flex-col justify-between">
                    <div class="w-full aspect-square bg-slate-300 rounded-2xl neumorphic-inset mb-3 flex items-center justify-center text-slate-400 text-xs">
                        [ Foto Produk 1 ]
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 truncate">Coca Cola</h3>
                        <p class="text-xs font-bold text-emerald-600 mt-0.5">Rp 8.000</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[10px] text-slate-400 font-medium">Stok: <b class="text-slate-600">5</b></span>
                            <button class="neumorphic-btn w-8 h-8 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-lg">+</button>
                        </div>
                    </div>
                </div>

                <div class="neumorphic-flat p-3.5 rounded-3xl flex flex-col justify-between">
                    <div class="w-full aspect-square bg-slate-300 rounded-2xl neumorphic-inset mb-3 flex items-center justify-center text-slate-400 text-xs">
                        [ Foto Produk 2 ]
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 truncate">Pocari Sweat</h3>
                        <p class="text-xs font-bold text-emerald-600 mt-0.5">Rp 9.500</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[10px] text-slate-400 font-medium">Stok: <b class="text-slate-600">3</b></span>
                            <button class="neumorphic-btn w-8 h-8 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-lg">+</button>
                        </div>
                    </div>
                </div>

                <div class="neumorphic-flat p-3.5 rounded-3xl flex flex-col justify-between">
                    <div class="w-full aspect-square bg-slate-300 rounded-2xl neumorphic-inset mb-3 flex items-center justify-center text-slate-400 text-xs">
                        [ Foto Produk 3 ]
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 truncate">Ultra Milk Choco</h3>
                        <p class="text-xs font-bold text-emerald-600 mt-0.5">Rp 7.000</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[10px] text-slate-400 font-medium">Stok: <b class="text-slate-600">8</b></span>
                            <button class="neumorphic-btn w-8 h-8 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-lg">+</button>
                        </div>
                    </div>
                </div>

                <div class="neumorphic-flat p-3.5 rounded-3xl flex flex-col justify-between">
                    <div class="w-full aspect-square bg-slate-300 rounded-2xl neumorphic-inset mb-3 flex items-center justify-center text-slate-400 text-xs">
                        [ Foto Produk 4 ]
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 truncate">Teh Pucuk Harum</h3>
                        <p class="text-xs font-bold text-emerald-600 mt-0.5">Rp 4.000</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[10px] text-slate-400 font-medium">Stok: <b class="text-slate-600">12</b></span>
                            <button class="neumorphic-btn w-8 h-8 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-lg">+</button>
                        </div>
                    </div>
                </div>

                <div class="neumorphic-flat p-3.5 rounded-3xl flex flex-col justify-between">
                    <div class="w-full aspect-square bg-slate-300 rounded-2xl neumorphic-inset mb-3 flex items-center justify-center text-slate-400 text-xs">
                        [ Foto Produk 5 ]
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 truncate">Aqua 600ml</h3>
                        <p class="text-xs font-bold text-emerald-600 mt-0.5">Rp 3.500</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[10px] text-slate-400 font-medium">Stok: <b class="text-slate-600">0</b></span>
                            <button class="neumorphic-inset w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 cursor-not-allowed text-sm" disabled>×</button>
                        </div>
                    </div>
                </div>

                <div class="neumorphic-flat p-3.5 rounded-3xl flex flex-col justify-between">
                    <div class="w-full aspect-square bg-slate-300 rounded-2xl neumorphic-inset mb-3 flex items-center justify-center text-slate-400 text-xs">
                        [ Foto Produk 6 ]
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 truncate">Silverqueen</h3>
                        <p class="text-xs font-bold text-emerald-600 mt-0.5">Rp 15.000</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[10px] text-slate-400 font-medium">Stok: <b class="text-slate-600">4</b></span>
                            <button class="neumorphic-btn w-8 h-8 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-lg">+</button>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <nav class="absolute bottom-0 left-0 right-0 p-4 bg-[#E2E8F0]/90 backdrop-blur-md border-t border-slate-200/50">
            <div class="neumorphic-flat p-2 rounded-2xl flex items-center justify-around">
                <button class="neumorphic-inset p-3 rounded-xl text-emerald-600 flex flex-col items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </button>

                <button class="neumorphic-btn p-3 rounded-xl text-slate-400 hover:text-slate-600 flex flex-col items-center gap-1 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-emerald-500 text-white text-[9px] font-extrabold w-4 h-4 rounded-full flex items-center justify-center shadow">2</span>
                </button>

                <button class="neumorphic-btn p-3 rounded-xl text-slate-400 hover:text-slate-600 flex flex-col items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </button>
            </div>
        </nav>

    </div>

</body>

</html>