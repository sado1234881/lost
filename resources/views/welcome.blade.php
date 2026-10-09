<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SJUT Lost & Found — St. John's University of Tanzania</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-zinc-950 text-slate-800 dark:text-zinc-100 min-h-screen flex flex-col antialiased selection:bg-purple-700 selection:text-white">

    <!-- GLOBAL HEADER -->
    @include('layouts.partials.header')

    <!-- HERO SECTION -->
    <section class="relative bg-gradient-to-b from-purple-950 via-indigo-950 to-slate-950 text-white pt-16 pb-20 px-4 lg:px-8 overflow-hidden">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 right-0 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto text-center space-y-8 relative z-10">
            <!-- SJUT Logo Emblem Centerpiece -->
            <div class="inline-flex flex-col items-center gap-3">
                <div class="p-2.5 rounded-2xl bg-white/95 shadow-xl border-2 border-amber-400/80 backdrop-blur-md">
                    <img src="{{ asset('images/sjut-logo.png') }}" alt="SJUT Crest" class="h-20 md:h-24 w-auto object-contain">
                </div>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-purple-900/60 border border-purple-500/40 text-purple-200 text-xs font-extrabold uppercase tracking-widest shadow-inner">
                    <i class="fa-solid fa-shield-halved text-amber-400"></i>
                    St. John’s University of Tanzania Platform
                </div>
            </div>

            <!-- Large Primary Headline -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight max-w-4xl mx-auto">
                Lost Something? Found Something? <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-amber-300 via-amber-400 to-amber-200 bg-clip-text text-transparent">Let’s Help It Find Its Way Home.</span>
            </h1>

            <!-- Supporting Description -->
            <p class="text-slate-300 text-base md:text-lg max-w-3xl mx-auto font-normal leading-relaxed">
                <strong class="text-white">SJUT Lost & Found</strong> — A trusted digital platform for the St. John’s University of Tanzania community to report, discover, match and recover lost property.
            </p>
        </div>
    </section>

    <!-- "HOW CAN WE HELP YOU TODAY?" ENTRY POINT SECTION -->
    <section class="py-16 px-4 lg:px-8 -mt-8 relative z-20">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">How can we help you today?</h2>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Select an option below to initiate property tracking or recovery.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Primary Action Card: Lost Property --}}
                <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-8 shadow-xl flex flex-col justify-between space-y-6 hover:border-amber-400 transition-all group">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">I Lost Something</h3>
                            <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                                Report an item you’ve lost and let the system help you find a possible match across campus security logs.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('lost-items.create') }}" class="w-full py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-md transition-colors flex items-center justify-center gap-2">
                        <span>Report Lost Item</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                {{-- Primary Action Card: Found Property --}}
                <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-8 shadow-xl flex flex-col justify-between space-y-6 hover:border-purple-600 transition-all group">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-purple-900/10 text-purple-700 dark:text-purple-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-hand-holding-hand"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">I Found Something</h3>
                            <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                                Report an item you’ve found on campus and help return it to its rightful student or staff owner.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('found-items.create') }}" class="w-full py-3.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-white font-extrabold text-xs shadow-md transition-colors flex items-center justify-center gap-2">
                        <span>Report Found Item</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>



    <!-- WHY USE SJUT LOST & FOUND SECTION -->
    <section class="py-20 px-4 lg:px-8 bg-slate-50 dark:bg-zinc-950 border-t border-slate-200 dark:border-zinc-800">
        <div class="max-w-7xl mx-auto space-y-12">
            <div class="text-center space-y-3 max-w-2xl mx-auto">
                <span class="text-xs font-extrabold text-purple-700 dark:text-purple-400 uppercase tracking-widest block">System Integrity</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Why Use SJUT Lost & Found?</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400">Built specifically to serve St. John's University of Tanzania students, faculty, and administrative staff.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-6 rounded-2xl bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-circle-plus"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">Easy Reporting</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                        Report lost or found property through a clean, structured process that captures all necessary identifying details.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">Smart Matching</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                        Use the system's matching capabilities to automatically discover potentially matching property records across campus.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">Secure Communication</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                        Communicate directly with item finders or security personnel via the integrated Direct Messaging hub.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">Verified Recovery</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                        Support the official claim and verification process before property is released at the Security Office.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">In-System Notifications & Alerts</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                        Stay informed through instant in-system notifications delivered to your account whenever matches or claim status updates occur.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">Community Responsibility</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                        Help fellow SJUT students, lecturers, and staff recover their belongings responsibly and safely.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT SJUT SECTION -->
    <section id="about-sjut" class="py-20 px-4 lg:px-8 bg-white dark:bg-zinc-900 border-t border-slate-200 dark:border-zinc-800">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-purple-100 text-purple-900 dark:bg-purple-950 dark:text-purple-300 text-xs font-bold uppercase tracking-wider">
                    Institution Context
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">About St. John’s University of Tanzania</h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-zinc-300 leading-relaxed">
                    <strong>St. John’s University of Tanzania (SJUT)</strong> is a higher learning institution located in Dodoma, Tanzania, committed to quality education, holistic development, research, and service to society. Established in 2007 by the Anglican Church of Tanzania, SJUT provides a nurturing environment for academic excellence and community responsibility.
                </p>

                <div class="grid grid-cols-2 gap-4 text-xs pt-2">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/80 border border-slate-200 dark:border-zinc-700">
                        <span class="font-bold text-purple-800 dark:text-purple-400 uppercase text-[10px] block mb-1">Official Motto</span>
                        <p class="font-serif italic font-semibold text-slate-900 dark:text-white">“To Learn To Serve”</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/80 border border-slate-200 dark:border-zinc-700">
                        <span class="font-bold text-blue-800 dark:text-blue-400 uppercase text-[10px] block mb-1">Location</span>
                        <p class="font-semibold text-slate-900 dark:text-white">Dodoma, Tanzania</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="p-8 rounded-3xl bg-gradient-to-br from-purple-950 via-indigo-950 to-slate-950 text-white space-y-6 shadow-2xl border border-purple-500/30 relative text-center">
                    <div class="flex flex-col items-center justify-center gap-3 border-b border-purple-800/60 pb-6">
                        <div class="p-2 rounded-2xl bg-white/95 shadow-lg border border-amber-400/80">
                            <img src="{{ asset('images/sjut-logo.png') }}" alt="SJUT Official Crest" class="h-20 w-auto object-contain">
                        </div>
                        <div>
                            <h3 class="font-extrabold text-lg text-white">St. John’s University of Tanzania</h3>
                            <span class="text-xs text-amber-300">“To Learn To Serve” • Since 2007</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        This web-based lost property recovery system is developed to enhance transparency, streamline security office intakes, and eliminate property loss frustration across the SJUT campus community.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION BANNER (BROWSE REGISTRY & GUIDANCE) -->
    <section class="py-16 px-4 lg:px-8 bg-gradient-to-r from-purple-950 via-indigo-950 to-blue-950 text-white text-center">
        <div class="max-w-4xl mx-auto space-y-6">
            <h2 class="text-2xl sm:text-4xl font-extrabold">Explore the Campus Property Registry</h2>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto">Browse discovered items across the SJUT community or consult the official user guide.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                <a href="{{ route('found-items.index') }}" class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-purple-700 hover:bg-purple-600 text-white font-extrabold text-xs shadow-lg border border-purple-400/30 transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-amber-300"></i>
                    <span>Browse Found Property Registry</span>
                </a>
                <a href="{{ route('lost-items.index') }}" class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-extrabold text-xs shadow-lg border border-white/20 transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-list-check text-blue-300"></i>
                    <span>Browse Lost Property Records</span>
                </a>
                <a href="{{ route('guide.index') }}" class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-lg transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-book-open"></i>
                    <span>View User Guide</span>
                </a>
            </div>
        </div>
    </section>

    <!-- GLOBAL FOOTER -->
    @include('layouts.partials.footer')

    <!-- GLOBAL FLOATING CHATBOT ASSISTANT -->
    @include('components.chatbot')

</body>
</html>
