<header class="sticky top-0 z-40 bg-white/70 backdrop-blur-xl border-b border-outline-variant/10 px-10 py-4 flex items-center justify-between">

    {{-- Page title (injected by each page) --}}
    <div>
        <p class="text-[10px] uppercase tracking-[0.2em] text-primary font-extrabold font-['Manrope']">
            @yield('breadcrumb', 'Curation Hub')
        </p>
        <h1 class="font-['Plus_Jakarta_Sans'] font-extrabold text-xl tracking-tight text-on-surface">
            @yield('page-title', 'Dashboard')
        </h1>
    </div>

    {{-- Right actions --}}
    <div class="flex items-center gap-3">
        {{-- Search --}}
        <div class="flex items-center bg-surface-container rounded-full px-4 py-2 gap-2 w-56">
            <span class="material-symbols-outlined text-on-surface-variant text-lg">search</span>
            <input
                type="text"
                placeholder="Search..."
                class="bg-transparent border-none focus:ring-0 text-sm w-full font-['Manrope'] placeholder:text-outline/50"
            />
        </div>

        {{-- Notifications --}}
        <button class="w-9 h-9 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-xl">notifications</span>
        </button>

        {{-- Settings --}}
        <button class="w-9 h-9 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-xl">settings</span>
        </button>
    </div>

</header>