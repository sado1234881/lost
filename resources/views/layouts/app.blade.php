<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Workspace') — SJUT Lost Property Recovery System</title>

    <!-- Theme Initialization Engine (Prevents Flicker) -->
    <script>
        const theme = localStorage.getItem('theme') || 'system';
        if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function setTheme(theme) {
            if (theme === 'system') {
                localStorage.removeItem('theme');
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } else {
                localStorage.setItem('theme', theme);
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
            const globalMenu = document.getElementById('global-theme-menu');
            if (globalMenu) globalMenu.classList.add('hidden');
        }
    </script>

    <!-- Google Fonts & Font Awesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-zinc-950 text-slate-800 dark:text-zinc-100 min-h-screen flex flex-col antialiased selection:bg-purple-700 selection:text-white">
    <div id="app-wrapper" class="min-h-screen flex flex-col justify-between">

        <!-- GLOBAL HEADER -->
        @include('layouts.partials.header')

        <!-- MAIN PAGE-SPECIFIC CONTENT CONTAINER -->
        <main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full pb-20 sm:pb-24">

            <!-- ACTION REQUIRED COMPLIANCE BANNER -->
            @auth
                @if(Auth::user()->action_required)
                    <div class="mb-6 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-900 dark:text-amber-200 text-xs md:text-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm animate-pulse">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center text-base font-extrabold shrink-0">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <span class="font-extrabold text-amber-700 dark:text-amber-300 block">ACTION REQUIRED</span>
                                <p class="text-xs text-amber-800 dark:text-amber-300/80">{{ Auth::user()->action_required_message ?? 'You indicated that you found an item. Please complete the Found Item Report.' }}</p>
                            </div>
                        </div>
                        <a href="{{ Auth::user()->action_required_url ?? route('found-items.create') }}" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-md transition-colors shrink-0">
                            Complete Action &rarr;
                        </a>
                    </div>
                @endif
            @endauth

            <!-- FLASH ALERTS -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs md:text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-lg text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-base cursor-pointer">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 text-xs md:text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation text-lg text-red-600"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-base cursor-pointer">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs md:text-sm space-y-1 shadow-sm">
                    <div class="font-bold flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> Please correct the following errors:
                    </div>
                    <ul class="list-disc list-inside pl-4 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- VIEW CONTENT HOOK -->
            @yield('content')

        </main>

        <!-- GLOBAL FOOTER -->
        @include('layouts.partials.footer')

    </div>

    <!-- GLOBAL FLOATING CHATBOT ASSISTANT -->
    @include('components.chatbot')

    @stack('scripts')
</body>
</html>
