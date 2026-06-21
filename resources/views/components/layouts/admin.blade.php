<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>{{ $title ?? 'Admin Dashboard' }} - Vending Machine</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

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
            transition: all 0.2s ease;
        }
        .neumorphic-btn:active {
            box-shadow: inset 2px 2px 5px #cbd5e1, inset -2px -2px 5px #ffffff;
        }
        .neumorphic-active {
            box-shadow: inset 3px 3px 6px #cbd5e1, inset -3px -3px 6px #ffffff;
            color: #10b981; /* emerald-500 */
        }
        
        /* Custom scrollbar for mobile */
        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
    </style>
</head>
<body class="flex justify-center items-center min-h-screen text-slate-700 antialiased">

    <!-- Container Utama: Max width MD (Mobile First), Bayangan 2XL, Latar Belakang Abu-abu Lembut -->
    <div class="w-full max-w-md min-h-screen bg-[#E2E8F0] flex flex-col justify-between relative shadow-2xl overflow-x-hidden pb-24">

        <!-- Header Minimalis -->
        <header class="px-6 pt-8 pb-4 shrink-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 font-medium tracking-wide uppercase">Admin Panel</p>
                    <h1 class="text-xl font-bold text-slate-800">{{ $title ?? 'Vending Control' }}</h1>
                </div>
                <div class="neumorphic-flat px-3 py-1.5 rounded-full flex items-center gap-1.5 text-[10px] font-bold text-emerald-500 uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Online
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="px-6 flex-1 overflow-y-auto">
            {{ $slot }}
        </main>

        <!-- Bottom Navigation Bar (Neumorphic) -->
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md p-4 bg-[#E2E8F0]/90 backdrop-blur-md z-50">
            <div class="neumorphic-flat p-2 rounded-2xl flex items-center justify-between px-4">
                
                <!-- Dashboard -->
                <a href="/admin" class="{{ request()->is('admin') ? 'neumorphic-active' : 'neumorphic-btn text-slate-400 hover:text-slate-600' }} p-3 rounded-xl flex flex-col items-center gap-1 w-12 h-12 justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>

                <!-- Products -->
                <a href="/admin/products" class="{{ request()->is('admin/products*') ? 'neumorphic-active' : 'neumorphic-btn text-slate-400 hover:text-slate-600' }} p-3 rounded-xl flex flex-col items-center gap-1 w-12 h-12 justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </a>

                <!-- Machines -->
                <a href="/admin/machines" class="{{ request()->is('admin/machines*') ? 'neumorphic-active' : 'neumorphic-btn text-slate-400 hover:text-slate-600' }} p-3 rounded-xl flex flex-col items-center gap-1 w-12 h-12 justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                    </svg>
                </a>

                <!-- Orders -->
                <a href="/admin/orders" class="{{ request()->is('admin/orders*') ? 'neumorphic-active' : 'neumorphic-btn text-slate-400 hover:text-slate-600' }} p-3 rounded-xl flex flex-col items-center gap-1 w-12 h-12 justify-center relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </a>
                
                <!-- Issues -->
                <a href="/admin/issues" class="{{ request()->is('admin/issues*') ? 'neumorphic-active text-red-500' : 'neumorphic-btn text-red-400 hover:text-red-600' }} p-3 rounded-xl flex flex-col items-center gap-1 w-12 h-12 justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </a>

            </div>
        </nav>

    </div>

    @livewireScripts
</body>
</html>
