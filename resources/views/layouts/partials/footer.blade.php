<footer class="bg-slate-950 text-slate-400 border-t border-slate-800 pt-12 pb-24 sm:pb-20 px-4 lg:px-8 text-xs mt-auto relative z-10">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
        <div class="space-y-3">
            <div class="flex items-center gap-2.5 text-white font-extrabold text-sm">
                <img src="{{ asset('images/sjut-logo.png') }}" alt="SJUT Crest" class="h-8 w-auto object-contain">
                <span>St. John’s University of Tanzania</span>
            </div>
            <p class="text-slate-400 leading-relaxed text-[11px]">
                Web-Based Lost Property Recovery System.
                Dodoma, Tanzania.
            </p>
            <p class="italic text-amber-300 font-serif text-[11px]">“To Learn To Serve”</p>
        </div>

        <div>
            <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">Useful Links</h4>
            <ul class="space-y-2 text-[11px]">
                <li><a href="{{ route('landing') }}" class="hover:text-white transition-colors">Home</a></li>
                <li><a href="{{ route('guide.index') }}" class="hover:text-white transition-colors flex items-center gap-1.5"><i class="fa-solid fa-book-open text-[10px] text-purple-400"></i> User Guide</a></li>
                <li><a href="{{ route('landing') }}#about-sjut" class="hover:text-white transition-colors">About SJUT</a></li>
                <li><a href="{{ route('lost-items.create') }}" class="hover:text-white transition-colors">Report Lost Item</a></li>
                <li><a href="{{ route('found-items.create') }}" class="hover:text-white transition-colors">Report Found Item</a></li>
                @guest
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Sign In</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Create Account</a></li>
                @else
                    <li><a href="{{ route('workspace') }}" class="hover:text-white transition-colors">My Workspace</a></li>
                @endguest
            </ul>
        </div>

        <div>
            <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">Security Office</h4>
            <ul class="space-y-2 text-[11px]">
                <li>Central Security Office Desk</li>
                <li>Main Campus, Dodoma, Tanzania</li>
                <li>Email: security@sjut.ac.tz</li>
                <li>Phone: +255 26 239 0044</li>
            </ul>
        </div>

        <div class="pr-6 sm:pr-10">
            <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">Project Disclaimer</h4>
            <p class="text-[11px] leading-relaxed text-slate-400">
                This digital platform is developed as a final-year academic project for St. John's University of Tanzania (SJUT).
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto border-t border-slate-900 pt-6 text-center text-[11px] text-slate-500 pr-16 sm:pr-20">
        <p>&copy; {{ date('Y') }} St. John’s University of Tanzania (SJUT). All rights reserved.</p>
    </div>
</footer>
