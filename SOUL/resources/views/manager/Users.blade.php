@extends('layouts.dashboard')

@section('title', 'User Directory - Dashboard')
@section('breadcrumb', 'Organization')
@section('page-title', 'User Directory')

@php $activePage = 'users'; @endphp

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
    <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-6 mb-10">
        <div>
            <h2 class="font-['Plus_Jakarta_Sans'] text-4xl font-extrabold tracking-tight text-on-surface mb-2">
                User Directory
            </h2>
            <p class="text-on-surface-variant leading-relaxed max-w-xl">
                Manage your community's access levels and monitor active participants within the curator ecosystem.
            </p>
        </div>
        {{-- Total count badge (fix #2: managers excluded from count) --}}
        <div class="bg-surface-container px-5 py-2.5 rounded-full flex items-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-sm text-primary">group</span>
            <span class="font-bold text-sm tracking-tight">{{ number_format($totalUsers) }} Users</span>
        </div>
    </div>

    {{-- ── SEARCH + ROLE FILTER BAR ─────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('dashboard.users') }}"
          id="filter-form"
          class="bg-surface-container-low rounded-2xl p-4 mb-8 flex flex-col lg:flex-row gap-4 items-stretch lg:items-center">

        {{-- Search input --}}
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline pointer-events-none">search</span>
            <input
                name="search"
                type="text"
                value="{{ $search }}"
                placeholder="Search by name or email..."
                class="w-full bg-surface-container-lowest border-none rounded-xl pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-outline-variant font-['Manrope']"
            />
        </div>

        <div class="flex items-center gap-2 shrink-0">

            {{-- Role filter: 3 pill buttons --}}
            @foreach(['' => 'All', 'admin' => 'Admins', 'member' => 'Members'] as $value => $label)
                <button type="submit" name="role" value="{{ $value }}"
                        class="px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-colors
                               {{ $roleFilter === $value
                                   ? 'bg-primary text-on-primary shadow-md shadow-primary/20'
                                   : 'bg-surface-container-highest text-on-surface-variant hover:bg-surface-dim' }}">
                    {{ $label }}
                </button>
            @endforeach

            {{-- Clear button (only when something active) --}}
            @if($search || $roleFilter)
                <a href="{{ route('dashboard.users') }}"
                   class="w-10 h-10 flex items-center justify-center rounded-xl border border-outline-variant/30 text-on-surface-variant hover:bg-surface-container transition-colors"
                   title="Clear filters">
                    <span class="material-symbols-outlined text-sm">close</span>
                </a>
            @endif
        </div>
    </form>

    {{-- ── USER CARDS GRID ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @forelse($users as $user)
            <div class="bg-surface-container-lowest p-6 rounded-3xl transition-all hover:-translate-y-1 group editorial-shadow">

                {{-- Top row: avatar + role badge + delete --}}
                <div class="flex justify-between items-start mb-6">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl overflow-hidden ring-4 ring-surface-container-low bg-primary-container flex items-center justify-center">
                            <span class="font-['Plus_Jakarta_Sans'] font-extrabold text-xl text-primary">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>

                        {{-- Fix #1: correct badge per role (manager badge kept as fallback safety) --}}
                        @if($user->role === 'admin')
                            <span class="absolute -bottom-2 -right-2 bg-primary text-on-primary text-[10px] px-2 py-0.5 rounded-full font-black border-2 border-white uppercase tracking-wide">
                                Admin
                            </span>
                        @else
                            <span class="absolute -bottom-2 -right-2 bg-surface-container-highest text-on-secondary-container text-[10px] px-2 py-0.5 rounded-full font-black border-2 border-white uppercase tracking-wide">
                                Member
                            </span>
                        @endif
                    </div>

                    {{-- Delete button --}}
                    <button onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                            class="text-outline-variant hover:text-error transition-colors p-1 rounded-lg hover:bg-error-container/10"
                            title="Remove user">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>

                {{-- Name + email + section --}}
                <div class="mb-6">
                    <h3 class="font-['Plus_Jakarta_Sans'] text-xl font-bold text-on-surface group-hover:text-primary transition-colors">
                        {{ $user->name }}
                    </h3>
                    <p class="text-on-surface-variant text-sm font-medium truncate">{{ $user->email }}</p>
                    @if($user->section)
                        <p class="text-xs text-primary font-semibold mt-1.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">folder_shared</span>
                            {{ $user->section->name }}
                        </p>
                    @endif
                </div>

                {{-- Action: promote / demote --}}
                <div class="pt-5 border-t border-surface-container">
                    @if($user->role === 'admin')
                        <form method="POST" action="{{ route('dashboard.users.demote', $user) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full py-2.5 rounded-xl border border-outline-variant/30 text-on-surface-variant font-bold text-xs uppercase tracking-widest hover:bg-surface-container transition-colors">
                                Demote to Member
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('dashboard.users.promote', $user) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full py-2.5 rounded-xl bg-secondary-container text-on-secondary-container font-bold text-xs uppercase tracking-widest hover:brightness-95 transition-all">
                                Make Admin
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-3xl text-outline">search_off</span>
                </div>
                <h3 class="font-['Plus_Jakarta_Sans'] font-bold text-xl text-on-surface mb-1">No users found</h3>
                <p class="text-sm text-on-surface-variant">Try adjusting your search or filters.</p>
            </div>
        @endforelse

    </div>
    {{-- Fix #3: "Add New Member" placeholder card removed entirely --}}

    {{-- Pagination (preserves all active filters) --}}
    @if($users->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $users->links() }}
        </div>
    @endif


    {{-- ════════════════════════════════════════════════════════
         DELETE CONFIRM MODAL
    ════════════════════════════════════════════════════════ --}}
    <div id="modal-delete"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         role="dialog" aria-modal="true">

        <div class="absolute inset-0 bg-inverse-surface/30 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

        <div class="relative w-full max-w-md bg-surface-container-lowest rounded-2xl shadow-2xl overflow-hidden animate-modal">
            <div class="px-8 pt-8 pb-6 text-center">
                <div class="w-14 h-14 rounded-full bg-error-container/15 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-3xl text-error">person_remove</span>
                </div>
                <h2 class="font-['Plus_Jakarta_Sans'] text-xl font-extrabold text-on-surface mb-2">Remove User?</h2>
                <p class="text-sm text-on-surface-variant leading-relaxed">
                    You are about to permanently remove
                    <span id="delete-user-name" class="font-bold text-on-surface"></span>.
                    This cannot be undone.
                </p>
            </div>
            <form id="form-delete" method="POST" action="" class="px-8 pb-8 flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 py-3.5 rounded-full border border-outline-variant/30 text-on-surface-variant font-bold text-sm hover:bg-surface-container transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 py-3.5 rounded-full bg-error text-on-error font-bold text-sm hover:opacity-90 active:scale-95 transition-all shadow-md shadow-error/20">
                    Yes, Remove
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

    // ── Delete modal ─────────────────────────────────────────────────────────
    function openDeleteModal(id, name) {
        $id('delete-user-name').textContent = `"${name}"`;
        $id('form-delete').action = `/dashboard/users/${id}`;
        $id('modal-delete').classList.add('modal-open');
        lockScroll();
    }
    function closeDeleteModal() {
        $id('modal-delete').classList.remove('modal-open');
        unlockScroll();
    }

    // ── ESC to close ─────────────────────────────────────────────────────────
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeDeleteModal();
    });

    // ── Auto-dismiss flash after 4s ──────────────────────────────────────────
    ['flash-success', 'flash-error'].forEach(id => {
        const el = $id(id);
        if (el) setTimeout(() => el.remove(), 4000);
    });
</script>
@endpush