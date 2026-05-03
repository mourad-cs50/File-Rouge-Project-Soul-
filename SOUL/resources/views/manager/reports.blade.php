@extends('layouts.dashboard')

@section('title', 'Complaints Inbox - Dashboard')
@section('breadcrumb', 'Service Hub')
@section('page-title', 'Complaints Inbox')

@php $activePage = 'complaints'; @endphp

@section('content')

    {{-- Flash messages --}}
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

    {{-- PAGE HEADER --}}
    <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-6 mb-12">
        <div>
            <h2 class="font-['Plus_Jakarta_Sans'] text-5xl font-extrabold tracking-tight text-on-surface leading-none">
                Complaints <span class="text-primary-container">Inbox</span>
            </h2>
            <p class="text-on-surface-variant mt-2 text-sm">Review and respond to all incoming complaints from admins and members.</p>
        </div>
        <div class="flex gap-3 shrink-0">
            <div class="bg-surface-container px-6 py-4 rounded-2xl flex items-center gap-4">
                <span class="material-symbols-outlined text-primary text-3xl">pending_actions</span>
                <div>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-on-surface-variant">Active Issues</p>
                    <p class="text-xl font-bold text-on-surface font-['Plus_Jakarta_Sans']">{{ $activeCount }}</p>
                </div>
            </div>
            <div class="bg-primary px-6 py-4 rounded-2xl flex items-center gap-4 text-on-primary">
                <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">priority_high</span>
                <div>
                    <p class="text-[10px] uppercase tracking-wider font-bold opacity-80">Critical</p>
                    <p class="text-xl font-bold font-['Plus_Jakarta_Sans']">{{ str_pad($criticalCount, 2, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- TWO-COLUMN GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- ══════════════════════════
             LEFT — Admin Complaints
        ══════════════════════════ --}}
        <div class="space-y-6">

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-8 bg-primary rounded-full"></div>
                    <h2 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold text-on-surface">Admin Complaints</h2>
                </div>
                <span class="px-3 py-1 bg-secondary-fixed text-on-secondary-fixed text-xs font-bold rounded-full uppercase tracking-wide">
                    {{ $adminReports->where('status', 'pending')->count() }} New
                </span>
            </div>

            @forelse($adminReports as $report)
                <div class="bg-surface-container-lowest p-6 rounded-[2rem] editorial-shadow group hover:bg-surface-container transition-colors duration-300">

                    {{-- Card header --}}
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-primary-container flex items-center justify-center shrink-0">
                                <span class="font-['Plus_Jakarta_Sans'] font-extrabold text-lg text-primary">
                                    {{ strtoupper(substr($report->admin->name ?? 'A', 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="font-bold text-on-surface text-lg leading-tight">
                                    {{ $report->admin->name ?? 'Admin' }}
                                </h3>
                                <p class="text-xs text-on-surface-variant font-medium">
                                    {{ $report->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <span class="shrink-0 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight {{ $report->statusBadgeClass() }}">
                            {{ ucfirst($report->status) }}
                        </span>
                    </div>

                    {{-- Category --}}
                    <p class="text-xs font-bold text-on-surface uppercase tracking-wider mb-1">
                        {{ $report->categoryLabel() }}
                    </p>

                    {{-- Full content --}}
                    <div class="bg-white/50 p-4 rounded-xl mb-6 text-sm text-on-surface
                                {{ $report->isResolved() ? 'border-l-4 border-slate-300' : 'border-l-4 border-primary' }}">
                        {{ $report->description }}
                    </div>

                    {{-- Footer with Resolve button --}}
                    <div class="flex items-center justify-between">
                        @if($report->isResolved())
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-emerald-600 text-sm"
                                      style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                <span class="text-[10px] font-bold text-on-surface-variant uppercase">Case Closed</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                                <span class="text-[10px] font-bold text-on-surface-variant uppercase">Needs Response</span>
                            </div>
                            <form method="POST" action="{{ route('dashboard.reports.resolve', $report) }}">
                                @csrf
                                <button type="submit"
                                        class="bg-primary/10 text-primary px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-primary/20 active:scale-95 transition-all">
                                    Resolve
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center bg-surface-container-lowest rounded-[2rem] editorial-shadow">
                    <span class="material-symbols-outlined text-4xl text-outline mb-3">inbox</span>
                    <p class="font-bold text-on-surface">No reports</p>
                    <p class="text-sm text-on-surface-variant mt-1">No data available.</p>
                </div>
            @endforelse

        </div>

        {{-- ══════════════════════════
             RIGHT — Member Complaints (empty)
        ══════════════════════════ --}}
        <div class="space-y-6">

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-8 bg-tertiary rounded-full"></div>
                    <h2 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold text-on-surface">Member Complaints</h2>
                </div>
                <span class="px-3 py-1 bg-surface-container-highest text-on-surface-variant text-xs font-bold rounded-full uppercase tracking-wide">
                    0 Active
                </span>
            </div>

            @forelse($memberComplaints as $report)
                {{-- will never render --}}
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center bg-surface-container-low rounded-[2rem]">
                    <span class="material-symbols-outlined text-4xl text-outline mb-3">inbox</span>
                    <p class="font-bold text-on-surface">No member complaints</p>
                    <p class="text-sm text-on-surface-variant mt-1">All clear in this queue.</p>
                </div>
            @endforelse

        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div id="modal-delete"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         role="dialog" aria-modal="true">

        <div class="absolute inset-0 bg-inverse-surface/30 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

        <div class="relative w-full max-w-md bg-surface-container-lowest rounded-2xl shadow-2xl overflow-hidden animate-modal">
            <div class="px-8 pt-8 pb-6 text-center">
                <div class="w-14 h-14 rounded-full bg-error-container/15 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-3xl text-error">delete_forever</span>
                </div>
                <h2 class="font-['Plus_Jakarta_Sans'] text-xl font-extrabold text-on-surface mb-2">Delete Complaint?</h2>
                <p class="text-sm text-on-surface-variant leading-relaxed">
                    This will permanently remove the complaint and all its attachments.
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

    function openDeleteModal(complaintId) {
        $id('form-delete').action = `/dashboard/complaints/${complaintId}`;
        $id('modal-delete').classList.add('modal-open');
        lockScroll();
    }
    function closeDeleteModal() {
        $id('modal-delete').classList.remove('modal-open');
        unlockScroll();
    }

    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        closeDeleteModal();
    });

    ['flash-success', 'flash-error'].forEach(id => {
        const el = $id(id);
        if (el) setTimeout(() => el.remove(), 4000);
    });
</script>
@endpush