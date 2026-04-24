@extends('layouts.admin')
@section('title', 'admin Dashboard - The Curator')
@section('breadcrumb', 'Management Hub')
@section('page-title', 'Section Dashboard')


@php $activePage = 'section-dashboard'; @endphp

@section('content')

    {{-- ── Flash messages ──────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div id="flash-success"
             class="mb-8 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3.5 rounded-xl text-sm font-semibold">
            <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div id="flash-error"
             class="mb-8 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-xl text-sm font-semibold">
            <span class="material-symbols-outlined text-red-500 text-lg">error</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── PAGE HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-10">
        <h2 class="font-['Plus_Jakarta_Sans'] text-5xl font-extrabold tracking-tight text-on-surface mb-3">
            Section Dashboard
        </h2>
        <p class="text-on-surface-variant max-w-2xl leading-relaxed">
            Oversee and manage your active community members. Monitor engagement levels and maintain section integrity through direct moderation tools.
        </p>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         STATS BENTO GRID
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">

        {{-- Total Members --}}
        <div class="bg-surface-container-lowest rounded-xl p-8 border border-outline-variant/15 flex flex-col justify-between h-48 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-full transition-transform group-hover:scale-110"></div>
            <span class="text-xs font-bold uppercase tracking-widest text-primary font-['Manrope']">Total Members</span>
            <div class="flex items-baseline gap-2">
                <span class="font-['Plus_Jakarta_Sans'] text-5xl font-black text-on-surface">
                    {{ number_format($totalMembers) }}
                </span>
            </div>
            <div class="flex items-center gap-2 text-on-surface-variant text-sm">
                <span class="material-symbols-outlined text-sm">groups</span>
                All registered members
            </div>
        </div>

        {{-- Currently Active --}}
        <div class="bg-surface-container-low rounded-xl p-8 flex flex-col justify-between h-48 border border-outline-variant/10">
            <span class="text-xs font-bold uppercase tracking-widest text-secondary font-['Manrope']">Currently Active</span>
            <div class="flex items-baseline gap-3">
                <span class="font-['Plus_Jakarta_Sans'] text-5xl font-black text-on-surface">
                    {{ number_format($activeMembers) }}
                </span>
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
            </div>
            <div class="flex items-center gap-2 text-on-surface-variant text-sm">
                <span class="material-symbols-outlined text-sm">bolt</span>
                Active status count
            </div>
        </div>

        {{-- Section Health --}}
        <div class="bg-primary rounded-xl p-8 flex flex-col justify-between h-48 text-on-primary shadow-xl shadow-primary/20">
            <span class="text-xs font-bold uppercase tracking-widest opacity-80 font-['Manrope']">Section Health</span>
            <div class="flex items-baseline gap-2">
                <span class="font-['Plus_Jakarta_Sans'] text-5xl font-black">{{ $sectionHealth }}%</span>
            </div>
            <div class="flex items-center gap-2 text-sm opacity-90">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                Active members rate
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         MEMBER DIRECTORY TABLE
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm border border-outline-variant/10">

        {{-- Table header: title + search + status filter --}}
        <div class="px-8 py-6 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-surface-container/30 border-b border-outline-variant/10">
            <h3 class="font-['Plus_Jakarta_Sans'] text-xl font-bold text-on-surface">Member Directory</h3>

            <form method="GET" action="{{ route('admin.admindashboard') }}"
                  id="table-filter-form"
                  class="flex items-center gap-3 flex-wrap">

                {{-- Search --}}
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg pointer-events-none">search</span>
                    <input
                        name="search"
                        type="text"
                        value="{{ $search }}"
                        placeholder="Filter members..."
                        class="pl-10 pr-4 py-2 bg-surface-container-highest/50 border-none rounded-full text-sm w-56 focus:ring-2 focus:ring-primary/20 font-['Manrope']"
                    />
                </div>

                {{-- Status filter pills --}}
                @foreach(['' => 'All', 'active' => 'Active', 'pending' => 'Pending', 'banned' => 'Banned'] as $value => $label)
                    <button type="submit" name="status" value="{{ $value }}"
                            class="px-4 py-2 rounded-full font-bold text-xs uppercase tracking-widest transition-colors
                                   {{ $statusFilter === $value
                                       ? 'bg-primary text-on-primary shadow-md shadow-primary/20'
                                       : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }}">
                        {{ $label }}
                    </button>
                @endforeach

                {{-- Clear --}}
                @if($search || $statusFilter)
                    <a href="{{ route('admin.admindashboard') }}"
                       class="w-8 h-8 flex items-center justify-center rounded-full border border-outline-variant/30 text-on-surface-variant hover:bg-surface-container transition-colors"
                       title="Clear filters">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low/50">
                        <th class="px-8 py-4 text-[11px] font-bold uppercase tracking-widest text-on-surface-variant font-['Manrope']">
                            Member Details
                        </th>
                        <th class="px-8 py-4 text-[11px] font-bold uppercase tracking-widest text-on-surface-variant font-['Manrope']">
                            Section
                        </th>
                        <th class="px-8 py-4 text-[11px] font-bold uppercase tracking-widest text-on-surface-variant font-['Manrope']">
                            Status
                        </th>
                        <th class="px-8 py-4 text-[11px] font-bold uppercase tracking-widest text-on-surface-variant font-['Manrope']">
                            Join Date
                        </th>
                        <th class="px-8 py-4 text-[11px] font-bold uppercase tracking-widest text-on-surface-variant font-['Manrope'] text-right">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">

                    @forelse($members as $member)
                        <tr class="hover:bg-surface-container-low/30 transition-colors group">

                            {{-- Member details --}}
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-primary-container flex items-center justify-center shadow-sm shrink-0">
                                        <span class="font-['Plus_Jakarta_Sans'] font-extrabold text-lg text-primary">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-on-surface">{{ $member->name }}</div>
                                        <div class="text-xs text-on-surface-variant">{{ $member->email }}</div>
                                        @if($member->isAdmin())
                                            <span class="text-[10px] font-black uppercase tracking-wide text-primary">Admin</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Section --}}
                            <td class="px-8 py-5 text-sm text-on-surface-variant">
                                {{ $member->section?->name ?? '—' }}
                            </td>

                            {{-- Status badge --}}
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $member->statusBadgeClass() }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $member->statusDotClass() }}
                                                 {{ $member->isActive() ? 'animate-pulse' : '' }}">
                                    </span>
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>

                            {{-- Join date --}}
                            <td class="px-8 py-5 text-sm text-on-surface-variant">
                                {{ $member->created_at->format('M d, Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    @if($member->isPending())
                                        {{-- Approve --}}
                                        <form method="POST" action="{{ route('admin.admindashboard.approve', $member) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 rounded-full bg-primary text-on-primary text-xs font-bold transition-all active:scale-95 shadow-lg shadow-primary/20 hover:bg-primary-dim">
                                                Approve
                                            </button>
                                        </form>
                                        {{-- Ban pending user --}}
                                        <button onclick="openBanModal({{ $member->id }}, '{{ addslashes($member->name) }}')"
                                                class="px-4 py-2 rounded-full border border-error/20 text-error hover:bg-error/5 text-xs font-bold transition-all active:scale-95">
                                            Ban
                                        </button>

                                    @elseif($member->isActive())
                                        {{-- Ban active user --}}
                                        <button onclick="openBanModal({{ $member->id }}, '{{ addslashes($member->name) }}')"
                                                class="px-4 py-2 rounded-full border border-error/20 text-error hover:bg-error/5 text-xs font-bold transition-all active:scale-95">
                                            Ban Member
                                        </button>

                                    @elseif($member->isBanned())
                                        {{-- Restore banned user --}}
                                        <form method="POST" action="{{ route('dashboard.section-dashboard.activate', $member) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 rounded-full bg-primary text-on-primary text-xs font-bold transition-all active:scale-95 shadow-lg shadow-primary/20 hover:bg-primary-dim">
                                                Restore Active
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="material-symbols-outlined text-4xl text-outline">search_off</span>
                                    <p class="font-bold text-on-surface">No members found</p>
                                    <p class="text-sm text-on-surface-variant">Try adjusting your search or filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Table footer: count + pagination --}}
        <div class="px-8 py-5 bg-surface-container-low/20 flex items-center justify-between border-t border-outline-variant/10">
            <p class="text-xs text-on-surface-variant font-medium tracking-wide">
                Showing {{ $members->firstItem() ?? 0 }}–{{ $members->lastItem() ?? 0 }}
                of {{ number_format($members->total()) }} members
            </p>

            {{-- Pagination --}}
            @if($members->hasPages())
                <div class="flex items-center gap-1.5">
                    {{-- Previous --}}
                    @if($members->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant/20 text-outline opacity-40 cursor-not-allowed">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </span>
                    @else
                        <a href="{{ $members->previousPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant/30 text-on-surface-variant hover:bg-white transition-all">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($members->getUrlRange(max(1, $members->currentPage() - 2), min($members->lastPage(), $members->currentPage() + 2)) as $page => $url)
                        @if($page === $members->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary text-on-primary text-xs font-bold shadow-md shadow-primary/10">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant/30 text-on-surface-variant hover:bg-white transition-all text-xs font-bold">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($members->hasMorePages())
                        <a href="{{ $members->nextPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant/30 text-on-surface-variant hover:bg-white transition-all">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant/20 text-outline opacity-40 cursor-not-allowed">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════
         BAN CONFIRM MODAL
    ════════════════════════════════════════════════════════ --}}
    <div id="modal-ban"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         role="dialog" aria-modal="true">

        <div class="absolute inset-0 bg-inverse-surface/30 backdrop-blur-sm" onclick="closeBanModal()"></div>

        <div class="relative w-full max-w-md bg-surface-container-lowest rounded-2xl shadow-2xl overflow-hidden animate-modal">
            <div class="px-8 pt-8 pb-6 text-center">
                <div class="w-14 h-14 rounded-full bg-error-container/15 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-3xl text-error">block</span>
                </div>
                <h2 class="font-['Plus_Jakarta_Sans'] text-xl font-extrabold text-on-surface mb-2">Ban Member?</h2>
                <p class="text-sm text-on-surface-variant leading-relaxed">
                    You are about to ban
                    <span id="ban-member-name" class="font-bold text-on-surface"></span>.
                    They will lose access immediately. You can restore them later.
                </p>
            </div>
            <form id="form-ban" method="POST" action="" class="px-8 pb-8 flex gap-3">
                @csrf
                <button type="button" onclick="closeBanModal()"
                        class="flex-1 py-3.5 rounded-full border border-outline-variant/30 text-on-surface-variant font-bold text-sm hover:bg-surface-container transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 py-3.5 rounded-full bg-error text-on-error font-bold text-sm hover:opacity-90 active:scale-95 transition-all shadow-md shadow-error/20">
                    Yes, Ban
                </button>
            </form>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .modal-open       { display: flex !important; }
    body.modal-active { overflow: hidden; }

    @keyframes modalIn {
        from { opacity: 0; transform: translateY(14px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0)    scale(1);    }
    }
    .animate-modal { animation: modalIn 0.2s ease-out forwards; }
</style>
@endpush

@push('scripts')
<script>
    const $id = id => document.getElementById(id);
    const lockScroll   = () => document.body.classList.add('modal-active');
    const unlockScroll = () => document.body.classList.remove('modal-active');

    // ── Ban modal ─────────────────────────────────────────────────────────────
    function openBanModal(userId, userName) {
        $id('ban-member-name').textContent = `"${userName}"`;
        $id('form-ban').action = `/dashboard/section-dashboard/${userId}/ban`;
        $id('modal-ban').classList.add('modal-open');
        lockScroll();
    }
    function closeBanModal() {
        $id('modal-ban').classList.remove('modal-open');
        unlockScroll();
    }

    // ── ESC to close ─────────────────────────────────────────────────────────
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeBanModal();
    });

    // ── Auto-dismiss flash after 4s ──────────────────────────────────────────
    ['flash-success', 'flash-error'].forEach(id => {
        const el = $id(id);
        if (el) setTimeout(() => el.remove(), 4000);
    });
</script>
@endpush