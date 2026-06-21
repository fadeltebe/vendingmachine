<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>{{ $title ?? 'Store' }} - Vending Machine</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
        
        /* Colorful Gradients */
        .gradient-text {
            background: linear-gradient(135deg, #6366f1, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gradient-btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
            transition: all 0.2s ease;
        }
        .gradient-btn:active {
            transform: translateY(2px);
            box-shadow: 0 2px 10px rgba(99, 102, 241, 0.4);
        }
        
        /* Custom scrollbar for mobile */
        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
    </style>
</head>
<body class="flex justify-center items-center min-h-screen text-slate-700 antialiased relative">

    <!-- Container Utama: Max width MD (Mobile First), Bayangan 2XL -->
    <div class="w-full max-w-md min-h-screen bg-[#E2E8F0] flex flex-col relative shadow-2xl overflow-x-hidden">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
