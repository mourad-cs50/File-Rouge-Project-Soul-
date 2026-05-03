@extends('layouts.admin')

@section('title', 'Reported Posts - ' . $section->name)
@section('breadcrumb', 'Moderation Portal')
@section('page-title', 'Reported Posts')

@php $activePage = 'reported-posts'; @endphp

@section('content')

    {{-- ── Flash messages ──────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div id="flash-success"
             class="mb-8 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3.5 rounded-xl text-sm font-semibold">
            <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── PAGE HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-8">
        <h2 class="font-['Plus_Jakarta_Sans'] text-3xl font-extrabold tracking-tight text-on-surface mb-2">
            Reported Posts
        </h2>
        <p class="text-on-surface-variant font-medium">
            Section: <span class="font-bold text-primary">{{ $section->name }}</span>
            — review and take action on community reports.
        </p>
    </div>

    {{-- ── REASON FILTER PILLS ─────────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('admin.reported-posts') }}"
          class="flex gap-2 overflow-x-auto pb-4 mb-8 no-scrollbar flex-wrap">
        @foreach([
            ''                  => 'All Reports (' . $counts['all'] . ')',
            'spam'              => 'Spam (' . $counts['spam'] . ')',
            'harassment'        => 'Harassment (' . $counts['harassment'] . ')',
            'inappropriate'     => 'Inappropriate (' . $counts['inappropriate'] . ')',
            'false_information' => 'False Info (' . $counts['false_information'] . ')',
        ] as $value => $label)
            <button type="submit" name="reason" value="{{ $value }}"
                    class="px-5 py-2 rounded-full font-['Manrope'] text-sm font-semibold whitespace-nowrap transition-all
                           {{ $reasonFilter === $value
                               ? 'bg-primary text-on-primary shadow-md shadow-primary/20'
                               : 'bg-secondary-fixed text-on-secondary-fixed hover:brightness-95' }}">
                {{ $label }}
            </button>
        @endforeach
    </form>

    {{-- ── REPORT CARDS GRID ───────────────────────────────────────────────── --}}
    @if($reports->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <span class="material-symbols-outlined text-5xl text-outline mb-4">gavel</span>
            <h3 class="font-['Plus_Jakarta_Sans'] font-bold text-2xl text-on-surface mb-2">No reports found</h3>
            <p class="text-sm text-on-surface-variant">
                {{ $reasonFilter ? 'No ' . $reasonFilter . ' reports in your section.' : 'Your section has no reported posts right now.' }}
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($reports as $report)
                @php $post = $report->post; @endphp

                <div class="flex flex-col bg-surface-container-lowest rounded-[2rem] overflow-hidden shadow-[0_20px_40px_rgba(39,44,81,0.04)] hover:shadow-[0_20px_40px_rgba(39,44,81,0.08)] transition-all
                            {{ $report->status !== 'pending' ? 'opacity-60' : '' }}">

                    {{-- ── Post preview ──────────────────────────────────────── --}}
                    <div class="relative h-64">

                        {{-- Media preview --}}
                        @if($post && $post->type === 'image' && $post->mediaUrl())
                            <img src="{{ $post->mediaUrl() }}"
                                 alt="Reported content"
                                 class="w-full h-full object-cover"/>
                            @if($post->type === 'video')
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-5xl" style="font-variation-settings: 'FILL' 1;">play_circle</span>
                                </div>
                            @endif

                        @elseif($post && $post->type === 'video')
                            @if($post->thumbnailUrl())
                                <img src="{{ $post->thumbnailUrl() }}" alt="Video thumbnail" class="w-full h-full object-cover opacity-80"/>
                            @else
                                <div class="w-full h-full bg-inverse-surface flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white/30 text-6xl">videocam</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-5xl" style="font-variation-settings: 'FILL' 1;">play_circle</span>
                            </div>

                        @elseif($post && $post->type === 'text')
                            <div class="w-full h-full bg-surface-container-low flex items-center justify-center p-6 relative">
                                <div class="text-center">
                                    <span class="material-symbols-outlined text-primary/30 text-6xl mb-3 block">format_quote</span>
                                    <p class="font-['Plus_Jakarta_Sans'] font-bold text-lg text-on-surface italic leading-relaxed line-clamp-4">
                                        "{{ $post->body }}"
                                    </p>
                                </div>
                            </div>

                        @elseif($post && $post->type === 'audio')
                            <div class="w-full h-full bg-surface-container flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary/30 text-7xl">music_note</span>
                            </div>

                        @else
                            {{-- Post deleted --}}
                            <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
                                <div class="text-center">
                                    <span class="material-symbols-outlined text-outline text-5xl mb-2 block">delete_forever</span>
                                    <p class="text-sm text-on-surface-variant font-medium">Post no longer exists</p>
                                </div>
                            </div>
                        @endif

                        {{-- Risk/reason badge --}}
                        <div class="absolute top-4 left-4">
                            <span class="text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider {{ $report->reasonBadgeClass() }}">
                                {{ $report->riskLabel() }}
                            </span>
                        </div>

                        {{-- Reviewed overlay --}}
                        @if($report->status !== 'pending')
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                <span class="bg-white/90 text-on-surface font-bold text-sm px-4 py-2 rounded-full uppercase tracking-widest">
                                    {{ $report->status === 'kept' ? '✓ Kept' : '✗ Deleted' }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- ── Card body ─────────────────────────────────────────── --}}
                    <div class="p-6 flex flex-col flex-1">

                        {{-- Reporter info --}}
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center shrink-0">
                                <span class="font-bold text-xs text-on-surface-variant">
                                    {{ strtoupper(substr($report->reporter->name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <span class="font-['Plus_Jakarta_Sans'] font-bold text-sm block leading-none">
                                    @{{ $post?->user->name ?? 'Deleted User' }}
                                </span>
                                <span class="text-[10px] text-on-surface-variant font-semibold">
                                    Reported {{ $report->created_at->diffForHumans() }}
                                    by {{ $report->reporter->name }}
                                </span>
                            </div>
                        </div>

                        {{-- Report details --}}
                        <div class="mb-5 flex-1">
                            <h3 class="font-['Plus_Jakarta_Sans'] font-bold text-lg leading-tight mb-2">
                                {{ $report->reasonLabel() }}
                            </h3>
                            @if($report->details)
                                <p class="text-on-surface-variant text-sm line-clamp-2">
                                    {{ $report->details }}
                                </p>
                            @else
                                <p class="text-on-surface-variant text-sm italic opacity-60">No additional details provided.</p>
                            @endif
                        </div>

                        {{-- Action buttons --}}
                        @if($report->status === 'pending')
                            <div class="flex flex-col gap-3">
                                <div class="flex gap-2">
                                    {{-- Keep Post --}}
                                    <form method="POST"
                                          action="{{ route('dashboard.reported-posts.keep', $report) }}"
                                          class="flex-1">
                                        @csrf
                                        <button type="submit"
                                                class="w-full bg-primary text-on-primary py-3 rounded-xl font-bold text-sm active:scale-95 transition-transform hover:bg-primary-dim">
                                            Keep Post
                                        </button>
                                    </form>

                                    {{-- Delete Post --}}
                                    <button onclick="openDeleteModal({{ $report->id }})"
                                            class="flex-1 border border-error/20 text-error py-3 rounded-xl font-bold text-sm active:scale-95 transition-transform hover:bg-error/5">
                                        Delete Post
                                    </button>
                                </div>

                                {{-- Review Details --}}
                                @if($post)
                                    <button onclick="openDetailsModal(
                                                '{{ addslashes($post->user->name ?? 'Unknown') }}',
                                                '{{ addslashes($report->reasonLabel()) }}',
                                                '{{ addslashes($report->details ?? 'No additional details.') }}',
                                                '{{ $report->created_at->format('M d, Y · H:i') }}'
                                            )"
                                            class="w-full bg-surface-container text-primary py-3 rounded-xl font-bold text-sm hover:bg-surface-container-high transition-colors">
                                        Review Details
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="pt-3 border-t border-outline-variant/10">
                                <p class="text-xs text-on-surface-variant text-center font-medium">
                                    Reviewed {{ $report->reviewed_at?->format('M d, Y') ?? '—' }}
                                    @if($report->reviewer)
                                        by {{ $report->reviewer->name }}
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($reports->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $reports->links() }}
            </div>
        @endif
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
                    <span class="material-symbols-outlined text-3xl text-error">delete_forever</span>
                </div>
                <h2 class="font-['Plus_Jakarta_Sans'] text-xl font-extrabold text-on-surface mb-2">Delete Post?</h2>
                <p class="text-sm text-on-surface-variant leading-relaxed">
                    The reported post will be permanently removed. This action cannot be undone.
                </p>
            </div>
            <form id="form-delete" method="POST" action="" class="px-8 pb-8 flex gap-3">
                @csrf
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


    {{-- ════════════════════════════════════════════════════════
         REVIEW DETAILS MODAL
    ════════════════════════════════════════════════════════ --}}
    <div id="modal-details"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         role="dialog" aria-modal="true">

        <div class="absolute inset-0 bg-inverse-surface/30 backdrop-blur-sm" onclick="closeDetailsModal()"></div>

        <div class="relative w-full max-w-lg bg-surface-container-lowest rounded-2xl shadow-2xl overflow-hidden animate-modal">
            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-outline-variant/10">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-primary font-extrabold mb-0.5">Report Details</p>
                    <h2 class="font-['Plus_Jakarta_Sans'] text-xl font-extrabold text-on-surface">Case Review</h2>
                </div>
                <button onclick="closeDetailsModal()"
                        class="w-9 h-9 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="px-8 py-7 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <p class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant mb-1">Post Author</p>
                        <p class="font-bold text-on-surface text-sm" id="detail-author"></p>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <p class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant mb-1">Report Reason</p>
                        <p class="font-bold text-on-surface text-sm" id="detail-reason"></p>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4 col-span-2">
                        <p class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant mb-1">Reported At</p>
                        <p class="font-bold text-on-surface text-sm" id="detail-date"></p>
                    </div>
                </div>
                <div class="bg-surface-container-low rounded-xl p-4">
                    <p class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant mb-2">Reporter's Details</p>
                    <p class="text-sm text-on-surface leading-relaxed" id="detail-details"></p>
                </div>
                <button onclick="closeDetailsModal()"
                        class="w-full py-3.5 rounded-full border border-outline-variant/30 text-on-surface-variant font-bold text-sm hover:bg-surface-container transition-colors">
                    Close
                </button>
            </div>
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

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-4 {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
    const $id = id => document.getElementById(id);
    const lockScroll   = () => document.body.classList.add('modal-active');
    const unlockScroll = () => document.body.classList.remove('modal-active');

    // ── Delete modal ──────────────────────────────────────────────────────────
    function openDeleteModal(reportId) {
        $id('form-delete').action = `/dashboard/reported-posts/${reportId}/delete-post`;
        $id('modal-delete').classList.add('modal-open');
        lockScroll();
    }
    function closeDeleteModal() {
        $id('modal-delete').classList.remove('modal-open');
        unlockScroll();
    }

    // ── Details modal ─────────────────────────────────────────────────────────
    function openDetailsModal(author, reason, details, date) {
        $id('detail-author').textContent  = author;
        $id('detail-reason').textContent  = reason;
        $id('detail-details').textContent = details;
        $id('detail-date').textContent    = date;
        $id('modal-details').classList.add('modal-open');
        lockScroll();
    }
    function closeDetailsModal() {
        $id('modal-details').classList.remove('modal-open');
        unlockScroll();
    }

    // ── ESC to close ──────────────────────────────────────────────────────────
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        closeDeleteModal();
        closeDetailsModal();
    });

    // ── Auto-dismiss flash ────────────────────────────────────────────────────
    const flash = $id('flash-success');
    if (flash) setTimeout(() => flash.remove(), 4000);
</script>
@endpush