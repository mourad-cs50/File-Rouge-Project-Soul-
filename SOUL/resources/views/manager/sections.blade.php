@extends('layouts.dashboard')

@section('title', 'Section Management - Dashboard')
@section('breadcrumb', 'Curation Hub')
@section('page-title', 'Section Management')

@php $activePage = 'sections'; @endphp

@section('content')

    {{-- ── Flash message ───────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div id="flash-success"
             class="mb-8 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3.5 rounded-xl text-sm font-semibold">
            <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── PAGE HEADER ─────────────────────────────────────────────────────── --}}
    <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-8 mb-12">
        <div class="max-w-xl">
            <h2 class="font-['Plus_Jakarta_Sans'] text-4xl font-extrabold tracking-tight text-on-surface mb-3">
                Section Management
            </h2>
            <p class="text-on-surface-variant leading-relaxed">
                Orchestrate your organizational structure. Add new hubs, assign leadership, and monitor growth metrics in real-time.
            </p>
        </div>

        <button onclick="openCreateModal()"
                class="inline-flex items-center gap-2 bg-gradient-to-br from-primary to-primary-container text-on-primary px-7 py-3.5 rounded-full font-bold text-sm hover:scale-105 active:scale-95 transition-all shadow-md shadow-primary/20 shrink-0">
            <span class="material-symbols-outlined text-lg">add_circle</span>
            New Section
        </button>
    </div>

    {{-- ── CARDS GRID ───────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

        @forelse($sections as $section)
            <div class="group relative bg-surface-container-low rounded-xl overflow-hidden hover:bg-surface-container transition-colors duration-300 editorial-shadow">

                <div class="h-44 w-full overflow-hidden relative bg-gradient-to-br from-primary-container to-secondary-container">
                    <div class="absolute inset-0 bg-gradient-to-t from-surface-container-low to-transparent"></div>
                    <span class="absolute top-4 left-4 bg-secondary-fixed text-on-secondary-fixed text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                        {{ $section->tag ?? 'General' }}
                    </span>
                </div>

                <div class="p-6 pt-0 -mt-8 relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-surface-container-lowest flex items-center justify-center shadow-sm shrink-0 text-primary">
                            <span class="material-symbols-outlined text-2xl">manage_accounts</span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-['Plus_Jakarta_Sans'] font-bold text-xl text-on-surface truncate">{{ $section->name }}</h3>
                            <p class="text-xs text-on-surface-variant font-medium">Admin: {{ $section->admin?->name ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-6">
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase tracking-widest text-on-surface-variant font-black">Members</span>
                            <span class="text-2xl font-['Plus_Jakarta_Sans'] font-extrabold text-primary">{{ $section->members_count }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="openEditModal({{ $section->id }}, '{{ addslashes($section->name) }}', '{{ addslashes($section->tag ?? '') }}', {{ $section->admin_id ?? 'null' }})"
                                    class="w-10 h-10 rounded-full flex items-center justify-center bg-surface-container-highest text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container transition-colors active:scale-90"
                                    title="Edit section">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </button>
                            <button onclick="openDeleteModal({{ $section->id }}, '{{ addslashes($section->name) }}')"
                                    class="w-10 h-10 rounded-full flex items-center justify-center bg-error-container/10 text-error hover:bg-error-container hover:text-on-error-container transition-colors active:scale-90"
                                    title="Delete section">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse

        {{-- Add placeholder --}}
        <div onclick="openCreateModal()"
             class="border-2 border-dashed border-outline-variant/30 rounded-xl p-8 flex flex-col items-center justify-center text-center group cursor-pointer hover:border-primary/50 hover:bg-surface-container-low transition-all duration-300 min-h-[280px]">
            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-3xl text-primary">add_circle</span>
            </div>
            <h4 class="font-['Plus_Jakarta_Sans'] font-bold text-lg text-on-surface">Expand Your Network</h4>
            <p class="text-sm text-on-surface-variant max-w-[200px] mt-2 leading-relaxed">Scale your organization by defining a new operational section.</p>
        </div>

    </div>


    {{-- ════════════════════════════════════════════════════════
         CREATE MODAL
    ════════════════════════════════════════════════════════ --}}
    <div id="modal-create"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         role="dialog" aria-modal="true" aria-labelledby="modal-create-title">

        <div class="absolute inset-0 bg-inverse-surface/30 backdrop-blur-sm" onclick="closeCreateModal()"></div>

        <div class="relative w-full max-w-lg bg-surface-container-lowest rounded-2xl shadow-2xl shadow-primary/10 overflow-hidden animate-modal">

            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-outline-variant/10">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-primary font-extrabold mb-0.5">New Section</p>
                    <h2 id="modal-create-title" class="font-['Plus_Jakarta_Sans'] text-xl font-extrabold text-on-surface">
                        Initialize Section
                    </h2>
                </div>
                <button onclick="closeCreateModal()"
                        class="w-9 h-9 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form method="POST" action="{{ route('dashboard.sections.store') }}" class="px-8 py-7 space-y-5">
                @csrf

                {{-- Name --}}
                <div class="space-y-2">
                    <label for="c-name" class="block text-[10px] uppercase tracking-[0.12em] font-bold text-on-surface-variant ml-1">
                        Section Name <span class="text-error">*</span>
                    </label>
                    <input id="c-name" name="name" type="text" required
                           placeholder="e.g. Design Ops"
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3.5 rounded-xl bg-surface-container-high border-transparent focus:border-primary/20 focus:ring-0 focus:bg-surface-container-lowest text-on-surface placeholder:text-outline/40 transition-all font-medium text-sm"/>
                    @error('name')
                        <p class="text-xs text-error ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tag --}}
                <div class="space-y-2">
                    <label for="c-tag" class="block text-[10px] uppercase tracking-[0.12em] font-bold text-on-surface-variant ml-1">
                        Category Tag
                    </label>
                    <select id="c-tag" name="tag"
                            class="w-full px-4 py-3.5 rounded-xl bg-surface-container-high border-transparent focus:border-primary/20 focus:ring-0 focus:bg-surface-container-lowest text-on-surface transition-all font-medium text-sm">
                        <option value="">— No tag —</option>
                        @foreach(['Creative','Development','Operations','Marketing','Finance'] as $tag)
                            <option value="{{ $tag }}" {{ old('tag') === $tag ? 'selected' : '' }}>{{ $tag }}</option>
                        @endforeach
                    </select>
                    @error('tag')
                        <p class="text-xs text-error ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Admin --}}
                <div class="space-y-2">
                    <label for="c-admin" class="block text-[10px] uppercase tracking-[0.12em] font-bold text-on-surface-variant ml-1">
                        Responsible Admin <span class="text-error">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg pointer-events-none">manage_accounts</span>
                        <select id="c-admin" name="admin_id" required
                                class="w-full pl-10 pr-4 py-3.5 rounded-xl bg-surface-container-high border-transparent focus:border-primary/20 focus:ring-0 focus:bg-surface-container-lowest text-on-surface transition-all font-medium text-sm">
                            <option value="">— Select an admin —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('admin_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} — {{ $user->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('admin_id')
                        <p class="text-xs text-error ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeCreateModal()"
                            class="flex-1 py-3.5 rounded-full border border-outline-variant/30 text-on-surface-variant font-bold text-sm hover:bg-surface-container transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 py-3.5 rounded-full bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold text-sm hover:scale-[1.02] active:scale-95 transition-all shadow-md shadow-primary/20">
                        Create Section
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════
         EDIT MODAL
    ════════════════════════════════════════════════════════ --}}
    <div id="modal-edit"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         role="dialog" aria-modal="true" aria-labelledby="modal-edit-title">

        <div class="absolute inset-0 bg-inverse-surface/30 backdrop-blur-sm" onclick="closeEditModal()"></div>

        <div class="relative w-full max-w-lg bg-surface-container-lowest rounded-2xl shadow-2xl shadow-primary/10 overflow-hidden animate-modal">

            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-outline-variant/10">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-primary font-extrabold mb-0.5">Edit Section</p>
                    <h2 id="modal-edit-title" class="font-['Plus_Jakarta_Sans'] text-xl font-extrabold text-on-surface">Update Section Details</h2>
                </div>
                <button onclick="closeEditModal()"
                        class="w-9 h-9 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="form-edit" method="POST" action="" class="px-8 py-7 space-y-5">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="e-name" class="block text-[10px] uppercase tracking-[0.12em] font-bold text-on-surface-variant ml-1">
                        Section Name <span class="text-error">*</span>
                    </label>
                    <input id="e-name" name="name" type="text" required
                           class="w-full px-4 py-3.5 rounded-xl bg-surface-container-high border-transparent focus:border-primary/20 focus:ring-0 focus:bg-surface-container-lowest text-on-surface transition-all font-medium text-sm"/>
                </div>

                <div class="space-y-2">
                    <label for="e-tag" class="block text-[10px] uppercase tracking-[0.12em] font-bold text-on-surface-variant ml-1">Category Tag</label>
                    <select id="e-tag" name="tag"
                            class="w-full px-4 py-3.5 rounded-xl bg-surface-container-high border-transparent focus:border-primary/20 focus:ring-0 focus:bg-surface-container-lowest text-on-surface transition-all font-medium text-sm">
                        <option value="">— No tag —</option>
                        @foreach(['Creative','Development','Operations','Marketing','Finance'] as $tag)
                            <option value="{{ $tag }}">{{ $tag }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="e-admin" class="block text-[10px] uppercase tracking-[0.12em] font-bold text-on-surface-variant ml-1">
                        Responsible Admin <span class="text-error">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg pointer-events-none">manage_accounts</span>
                        <select id="e-admin" name="admin_id" required
                                class="w-full pl-10 pr-4 py-3.5 rounded-xl bg-surface-container-high border-transparent focus:border-primary/20 focus:ring-0 focus:bg-surface-container-lowest text-on-surface transition-all font-medium text-sm">
                            <option value="">— Select an admin —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} — {{ $user->email }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()"
                            class="flex-1 py-3.5 rounded-full border border-outline-variant/30 text-on-surface-variant font-bold text-sm hover:bg-surface-container transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 py-3.5 rounded-full bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold text-sm hover:scale-[1.02] active:scale-95 transition-all shadow-md shadow-primary/20">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════
         DELETE CONFIRM MODAL
    ════════════════════════════════════════════════════════ --}}
    <div id="modal-delete"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         role="dialog" aria-modal="true" aria-labelledby="modal-delete-title">

        <div class="absolute inset-0 bg-inverse-surface/30 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

        <div class="relative w-full max-w-md bg-surface-container-lowest rounded-2xl shadow-2xl overflow-hidden animate-modal">

            <div class="px-8 pt-8 pb-6 text-center">
                <div class="w-14 h-14 rounded-full bg-error-container/15 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-3xl text-error">delete_forever</span>
                </div>
                <h2 id="modal-delete-title" class="font-['Plus_Jakarta_Sans'] text-xl font-extrabold text-on-surface mb-2">Delete Section?</h2>
                <p class="text-sm text-on-surface-variant leading-relaxed">
                    You are about to permanently delete
                    <span id="delete-section-name" class="font-bold text-on-surface"></span>.
                    This action cannot be undone.
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
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .modal-open  { display: flex !important; }
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

    // ── scroll lock ──────────────────────────────────────────────────────────
    const lockScroll   = () => document.body.classList.add('modal-active');
    const unlockScroll = () => document.body.classList.remove('modal-active');

    // ── CREATE ───────────────────────────────────────────────────────────────
    function openCreateModal() {
        $id('modal-create').classList.add('modal-open');
        lockScroll();
        setTimeout(() => $id('c-name').focus(), 60);
    }
    function closeCreateModal() {
        $id('modal-create').classList.remove('modal-open');
        unlockScroll();
    }

    // ── EDIT ─────────────────────────────────────────────────────────────────
    function openEditModal(id, name, tag, adminId) {
        $id('e-name').value  = name;
        $id('e-tag').value   = tag || '';
        $id('e-admin').value = adminId ?? '';
        $id('form-edit').action = `/dashboard/sections/${id}`;

        $id('modal-edit').classList.add('modal-open');
        lockScroll();
        setTimeout(() => $id('e-name').focus(), 60);
    }
    function closeEditModal() {
        $id('modal-edit').classList.remove('modal-open');
        unlockScroll();
    }

    // ── DELETE ───────────────────────────────────────────────────────────────
    function openDeleteModal(id, name) {
        $id('delete-section-name').textContent = `"${name}"`;
        $id('form-delete').action = `/dashboard/sections/${id}`;
        $id('modal-delete').classList.add('modal-open');
        lockScroll();
    }
    function closeDeleteModal() {
        $id('modal-delete').classList.remove('modal-open');
        unlockScroll();
    }

    // ── ESC to close ─────────────────────────────────────────────────────────
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        closeCreateModal();
        closeEditModal();
        closeDeleteModal();
    });

    // ── Re-open create modal if validation failed on POST /sections ──────────
    @if($errors->hasAny(['name', 'tag', 'admin_id']))
        document.addEventListener('DOMContentLoaded', openCreateModal);
    @endif

    // ── Auto-dismiss flash after 4s ──────────────────────────────────────────
    const flash = $id('flash-success');
    if (flash) setTimeout(() => flash.remove(), 4000);
</script>
@endpush