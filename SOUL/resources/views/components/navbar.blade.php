<nav class="sticky top-0 w-full z-50 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl shadow-[0_20px_40px_rgba(39,44,81,0.06)]">
    <div class="flex items-center justify-between px-12 py-4 max-w-[1440px] mx-auto">

        {{-- Logo + Nav Links --}}
        <div class="flex items-center gap-12">
            <a href="{{ url('/') }}" class="text-2xl font-bold tracking-tighter text-[#0253cd] dark:text-[#789dff] font-['Plus_Jakarta_Sans']">
                The Curator
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="#" class="font-['Plus_Jakarta_Sans'] font-semibold tracking-tight text-slate-600 dark:text-slate-400 hover:text-[#0253cd] transition-colors duration-300">
                    Gallery
                </a>
                <a href="#" class="font-['Plus_Jakarta_Sans'] font-semibold tracking-tight text-slate-600 dark:text-slate-400 hover:text-[#0253cd] transition-colors duration-300">
                    Formats
                </a>
                <a href="#" class="font-['Plus_Jakarta_Sans'] font-semibold tracking-tight text-slate-600 dark:text-slate-400 hover:text-[#0253cd] transition-colors duration-300">
                    Communities
                </a>
                <a href="#" class="font-['Plus_Jakarta_Sans'] font-semibold tracking-tight text-slate-600 dark:text-slate-400 hover:text-[#0253cd] transition-colors duration-300">
                    About
                </a>
            </div>
        </div>

        {{-- Search + Actions --}}
        <div class="flex items-center gap-6">
            <div class="hidden lg:flex items-center bg-slate-100 dark:bg-slate-800 rounded-full px-4 py-2 gap-2">
                <span class="material-symbols-outlined text-slate-400 text-xl">search</span>
                <input
                    class="bg-transparent border-none focus:ring-0 text-sm w-48 font-['Manrope']"
                    placeholder="Search inspiration..."
                    type="text"
                />
            </div>

            <div class="flex items-center gap-4">
                <button class="material-symbols-outlined p-2 text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors">
                    notifications
                </button>
                <button class="material-symbols-outlined p-2 text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors">
                    account_circle
                </button>
                <button class="bg-[#0253cd] text-white px-6 py-2.5 rounded-full font-bold text-sm hover:bg-[#0048b5] transition-transform active:scale-95">
                    Create
                </button>
            </div>
        </div>

    </div>
</nav>