<!-- Top Institutional Banner Bar -->
<div class="bg-purple-950 text-purple-100 border-b border-purple-900/60 py-2 px-4 text-xs font-medium">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
        <div class="flex items-center gap-3 flex-wrap justify-center sm:justify-start">
            <span class="font-bold flex items-center gap-1.5">
                <img src="{{ asset('images/sjut-logo.png') }}" alt="SJUT Logo" class="h-5 w-auto inline-block">
                St. John's University of Tanzania (SJUT)
            </span>
            <span class="hidden md:inline text-purple-700">•</span>
            <span class="text-purple-200/90"><i class="fa-solid fa-location-dot text-amber-400 mr-1"></i> Dodoma, Tanzania</span>
        </div>
        <div class="flex items-center gap-4 text-[11px]">
            <span class="italic font-serif text-amber-300"><i class="fa-solid fa-quote-left text-[9px] mr-1"></i> To Learn To Serve <i class="fa-solid fa-quote-right text-[9px] ml-1"></i></span>
            <span class="text-purple-700">|</span>
            <span><i class="fa-solid fa-shield-halved text-emerald-400 mr-1"></i> Security Desk: +255 26 239 0044</span>
        </div>
    </div>
</div>

<!-- Main Global Navigation Bar -->
<header class="sticky top-0 z-50 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md border-b border-slate-200 dark:border-zinc-800 px-4 lg:px-8 py-3 shadow-xs">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <!-- SJUT Logo Crest & Title -->
        <a href="{{ route('landing') }}" class="flex items-center gap-3.5 group">
            <div class="p-1 rounded-xl bg-white dark:bg-zinc-800 shadow-sm border border-purple-200 dark:border-zinc-700 group-hover:scale-105 transition-transform">
                <img src="{{ asset('images/sjut-logo.png') }}" alt="SJUT Official Crest" class="h-10 md:h-11 w-auto object-contain">
            </div>
            <div>
                <span class="font-extrabold text-base md:text-lg text-slate-900 dark:text-white block leading-tight tracking-tight">SJUT LOST & FOUND</span>
                <span class="text-[10px] text-purple-700 dark:text-purple-400 font-extrabold tracking-wider uppercase block">St. John's University of Tanzania</span>
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden lg:flex items-center gap-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider">
            <a href="{{ route('landing') }}" class="hover:text-purple-800 dark:hover:text-purple-400 transition-colors {{ request()->routeIs('landing') ? 'text-purple-800 dark:text-purple-400 font-extrabold' : '' }}">Home</a>
            <a href="{{ route('guide.index') }}" class="hover:text-purple-800 dark:hover:text-purple-400 transition-colors flex items-center gap-1.5 {{ request()->routeIs('guide.*') ? 'text-purple-800 dark:text-purple-400 font-extrabold' : '' }}" title="System User Guide & Operational Manual">
                <i class="fa-solid fa-book-open text-amber-500 text-sm"></i>
                <span>User Guide</span>
            </a>
            <a href="{{ route('landing') }}#about-sjut" class="hover:text-purple-800 dark:hover:text-purple-400 transition-colors">About SJUT</a>

            <!-- Lost & Found Dropdown Menu -->
            <div class="relative group">
                <button class="flex items-center gap-1.5 hover:text-purple-800 dark:hover:text-purple-400 transition-colors py-2 cursor-pointer">
                    <span>Lost & Found</span>
                    <i class="fa-solid fa-angle-down text-[10px]"></i>
                </button>
                <div class="absolute top-full left-0 w-60 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-xl p-2 hidden group-hover:block animate-fade-in space-y-1 z-50">
                    <a href="{{ route('lost-items.create') }}" class="p-2.5 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 flex items-center gap-2.5 text-xs text-slate-800 dark:text-zinc-200 font-semibold transition-colors">
                        <i class="fa-solid fa-circle-exclamation text-amber-600"></i> Report Lost Item
                    </a>
                    <a href="{{ route('found-items.create') }}" class="p-2.5 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 flex items-center gap-2.5 text-xs text-slate-800 dark:text-zinc-200 font-semibold transition-colors">
                        <i class="fa-solid fa-hand-holding-hand text-emerald-600"></i> Report Found Item
                    </a>
                    <hr class="border-slate-100 dark:border-zinc-800 my-1">
                    <a href="{{ route('lost-items.index') }}" class="p-2.5 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 flex items-center gap-2.5 text-xs text-slate-800 dark:text-zinc-200 font-semibold transition-colors">
                        <i class="fa-solid fa-list-check text-blue-600"></i> Browse Lost Items
                    </a>
                    <a href="{{ route('found-items.index') }}" class="p-2.5 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 flex items-center gap-2.5 text-xs text-slate-800 dark:text-zinc-200 font-semibold transition-colors">
                        <i class="fa-solid fa-boxes-stacked text-purple-600"></i> Browse Found Items
                    </a>
                    @auth
                    <a href="{{ route('claims.index') }}" class="p-2.5 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 flex items-center gap-2.5 text-xs text-slate-800 dark:text-zinc-200 font-semibold transition-colors">
                        <i class="fa-solid fa-file-signature text-amber-500"></i> Ownership Claims
                    </a>
                    @endauth
                </div>
            </div>

            @auth
                <a href="{{ route('workspace') }}" class="hover:text-purple-800 dark:hover:text-purple-400 transition-colors {{ request()->routeIs('workspace') ? 'text-purple-800 dark:text-purple-400 font-extrabold' : '' }}">My Workspace</a>
                
                @if(Auth::user()->isStaff() || Auth::user()->isAdmin())
                    <a href="{{ route('staff.index') }}" class="hover:text-purple-800 dark:hover:text-purple-400 transition-colors {{ request()->routeIs('staff.*') ? 'text-purple-800 dark:text-purple-400 font-extrabold' : '' }}">
                        <span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 text-[10px] font-extrabold uppercase mr-1">Staff</span>
                        Portal
                    </a>
                @endif
            @endauth
        </nav>

        <!-- Right Header Actions (Theme, Messages, Alerts, Auth/User Dropdown) -->
        <div class="flex items-center gap-2.5">
            <!-- Theme Mode Selector Dropdown -->
            <div class="relative" id="global-theme-dropdown-container">
                <button onclick="toggleGlobalThemeDropdown()" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300 hover:bg-purple-50 dark:hover:bg-zinc-700 flex items-center justify-center text-sm transition-colors cursor-pointer" title="Toggle Light/Dark Theme">
                    <i class="fa-solid fa-moon dark:hidden text-purple-900"></i>
                    <i class="fa-solid fa-sun hidden dark:block text-amber-400"></i>
                </button>
                <div id="global-theme-menu" class="hidden absolute right-0 mt-2 w-36 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl shadow-xl py-1.5 z-50 text-xs">
                    <button onclick="setTheme('light')" class="w-full px-3 py-1.5 text-left flex items-center gap-2 hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-medium">
                        <i class="fa-solid fa-sun w-4 text-amber-500"></i> Light
                    </button>
                    <button onclick="setTheme('dark')" class="w-full px-3 py-1.5 text-left flex items-center gap-2 hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-medium">
                        <i class="fa-solid fa-moon w-4 text-purple-400"></i> Dark
                    </button>
                    <button onclick="setTheme('system')" class="w-full px-3 py-1.5 text-left flex items-center gap-2 hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-medium">
                        <i class="fa-solid fa-desktop w-4 text-slate-400"></i> System
                    </button>
                </div>
            </div>

            @auth
                <!-- Direct Messages Icon -->
                <a href="{{ route('messages.index') }}" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300 hover:bg-purple-50 dark:hover:bg-zinc-700 flex items-center justify-center text-sm transition-colors relative" title="Direct Messages">
                    <i class="fa-solid fa-comments text-purple-800 dark:text-purple-400"></i>
                    @if(Auth::user()->unreadMessagesCount() > 0)
                        <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center animate-pulse">
                            {{ Auth::user()->unreadMessagesCount() }}
                        </span>
                    @endif
                </a>

                <!-- System Alerts Icon -->
                <a href="{{ route('notifications.index') }}" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300 hover:bg-purple-50 dark:hover:bg-zinc-700 flex items-center justify-center text-sm transition-colors relative" title="System Alerts">
                    <i class="fa-solid fa-bell text-amber-500"></i>
                    @if(Auth::user()->unreadNotificationsCount() > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center animate-pulse">
                            {{ Auth::user()->unreadNotificationsCount() }}
                        </span>
                    @endif
                </a>

                <!-- User Account Profile Dropdown -->
                <div class="relative" id="global-profile-dropdown-container">
                    <button onclick="toggleGlobalProfileDropdown()" class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors border border-purple-200/60 dark:border-zinc-700">
                        <div class="w-8 h-8 rounded-lg bg-purple-900 text-amber-300 font-extrabold text-xs flex items-center justify-center uppercase shadow-sm">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <span class="text-xs font-bold text-slate-900 dark:text-white block leading-tight">{{ Str::limit(Auth::user()->name, 14) }}</span>
                            <span class="text-[10px] text-purple-700 dark:text-purple-400 capitalize block font-semibold">{{ Auth::user()->role }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-1"></i>
                    </button>
                    <div id="global-profile-menu" class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-2xl py-2 z-50 text-xs">
                        <div class="px-4 py-2.5 border-b border-slate-100 dark:border-zinc-800">
                            <p class="text-xs font-extrabold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="p-1 space-y-0.5">
                            <a href="{{ route('workspace') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-semibold">
                                <i class="fa-solid fa-briefcase w-4 text-purple-700"></i> My Workspace Overview
                            </a>
                            <a href="{{ route('claims.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-semibold">
                                <i class="fa-solid fa-file-signature w-4 text-amber-500"></i> Ownership Claims
                            </a>
                            <a href="{{ route('messages.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-semibold">
                                <i class="fa-solid fa-comments w-4 text-blue-500"></i> Direct Messages
                            </a>
                            <a href="{{ route('recovery.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-semibold">
                                <i class="fa-solid fa-clock-rotate-left w-4 text-emerald-500"></i> Recovery History
                            </a>
                            
                            @if(Auth::user()->isStaff() || Auth::user()->isAdmin())
                                <hr class="border-slate-100 dark:border-zinc-800 my-1">
                                <a href="{{ route('staff.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-semibold">
                                    <i class="fa-solid fa-user-shield w-4 text-emerald-600"></i> Staff Portal
                                </a>
                                <a href="{{ route('admin.release') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-semibold">
                                    <i class="fa-solid fa-qrcode w-4 text-rose-500"></i> QR Release Terminal
                                </a>
                            @endif

                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-semibold">
                                    <i class="fa-solid fa-users w-4 text-indigo-500"></i> Registered Users
                                </a>
                                <a href="{{ route('admin.security.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-semibold">
                                    <i class="fa-solid fa-shield-halved w-4 text-emerald-500"></i> Security Center
                                </a>
                                <a href="{{ route('categories.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-semibold">
                                    <i class="fa-solid fa-tags w-4 text-sky-500"></i> Item Categories
                                </a>
                            @endif

                            <hr class="border-slate-100 dark:border-zinc-800 my-1">
                            <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-purple-50 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-semibold">
                                <i class="fa-solid fa-user-gear w-4 text-slate-500"></i> Profile Settings
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-red-50 dark:hover:bg-red-950/30 text-red-600 dark:text-red-400 font-semibold text-left">
                                    <i class="fa-solid fa-right-from-bracket w-4"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-slate-700 dark:text-zinc-200 hover:text-purple-800 font-bold text-xs transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-right-to-bracket text-purple-700 dark:text-purple-400"></i> Login
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-white font-bold text-xs shadow-md shadow-purple-950/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-amber-400"></i> Register / Create Account
                </a>
            @endauth
        </div>
    </div>
</header>

<script>
    function toggleGlobalThemeDropdown() {
        const menu = document.getElementById('global-theme-menu');
        if (menu) menu.classList.toggle('hidden');
    }

    function toggleGlobalProfileDropdown() {
        const menu = document.getElementById('global-profile-menu');
        if (menu) menu.classList.toggle('hidden');
    }

    window.addEventListener('click', function(e) {
        const themeContainer = document.getElementById('global-theme-dropdown-container');
        const profileContainer = document.getElementById('global-profile-dropdown-container');

        if (themeContainer && !themeContainer.contains(e.target)) {
            const menu = document.getElementById('global-theme-menu');
            if (menu) menu.classList.add('hidden');
        }
        if (profileContainer && !profileContainer.contains(e.target)) {
            const menu = document.getElementById('global-profile-menu');
            if (menu) menu.classList.add('hidden');
        }
    });
</script>
