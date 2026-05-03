{{--
    Usage: @include('components.dashboard-sidebar')
    Expects: optional $activePage variable (e.g. 'sections', 'users', 'complaints')
--}}

@php $activePage = $activePage ?? 'sections'; @endphp

<aside class="w-64 shrink-0 bg-surface-container-lowest border-r border-outline-variant/20 flex flex-col min-h-screen sticky top-0">

    {{-- Brand --}}
    <div class="px-6 py-6 border-b border-outline-variant/10">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-[#0253cd] text-2xl">fingerprint</span>
            <span class="text-xl font-bold tracking-tighter text-[#0253cd] font-['Plus_Jakarta_Sans']">
                The Curator
            </span>
        </a>
    </div>

    {{-- Manager Profile --}}
    <div class="px-6 py-5 border-b border-outline-variant/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary-fixed shrink-0">
                <img
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAiglq1qQ7udidXRawhR1XbzWj5TdvU9m2tjRhyPeGVNz1z_cOIbAQ1MNt_YVgnOOOaamUKQ-NqiiLuMI6Hk4ZpXvoyr2oMvV7RA4TNSy3YSfgr2Ngi7lO7cKazH1iOV1RvXzlhyI-HjvyyEmH4_llcMMNTk3fgnKcRXP4EIWuy8uTYLlp6htggRpSicBwTT8H6QkXvchGIO5UkPGZGbDgfKIUydgQRo_a2ONNZE-nX02DEmJUnyR6hmhGxQ0pq6kOi9wbhE9blGXnQ"
                    alt="Manager Profile"
                    class="w-full h-full object-cover"
                />
            </div>
            <div class="min-w-0">
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Manager</p>
                <p class="text-sm font-bold text-on-surface truncate font-['Plus_Jakarta_Sans']">
                    {{ auth()->user()->name ?? 'Admin User' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1">
        <p class="text-[10px] uppercase tracking-[0.18em] text-on-surface-variant font-extrabold px-3 mb-3">
            Management
        </p>


        <a href="{{ route('admin.admindashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                  {{ $activePage === 'users' ? 'nav-link-active' : 'text-on-surface-variant hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-xl">group</span>
            Users
        </a>

        <a href="{{ route('admin.reports') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                  {{ $activePage === 'reports' ? 'nav-link-active' : 'text-on-surface-variant hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-xl">report</span>
            Reports
        </a>

        <a href="{{ route('admin.reported-posts') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                  {{ $activePage === 'complaints' ? 'nav-link-active' : 'text-on-surface-variant hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-xl">warning</span>
            reported posts
        </a>

        <a href="{{ route('admin.posts') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                  {{ $activePage === 'section-dashboard' ? 'nav-link-active' : 'text-on-surface-variant hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-xl">fact_check</span>
            Moderate
        </a>
    </nav>

    {{-- Footer actions --}}
    <div class="px-3 py-4 border-t border-outline-variant/10 space-y-1">
        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined text-xl">settings</span>
            Settings
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-error hover:bg-error-container/10 transition-all">
                <span class="material-symbols-outlined text-xl">logout</span>
                Sign Out
            </button>
        </form>
    </div>

</aside>