@extends('layouts.member')

@section('title', 'Fluid Curator | Member Feed')

@section('content')

    {{-- ── Flash messages ──────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div id="flash-success"
             class="fixed top-20 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3.5 rounded-xl text-sm font-semibold shadow-lg">
            <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div id="flash-error"
             class="fixed top-20 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-xl text-sm font-semibold shadow-lg">
            <span class="material-symbols-outlined text-red-500 text-lg">error</span>
            {{ session('error') }}
        </div>
    @endif

    <main class="max-w-xl mx-auto px-4 pt-24 pb-32 space-y-12">

        {{-- ── FEED HEADER + PUBLIC/FRIENDS TOGGLE ─────────────────────────── --}}
        <section class="flex flex-col gap-6">
            <div class="flex items-end justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-primary/60 mb-1 block font-['Manrope']">
                        Discovery
                    </span>
                    <h2 class="text-3xl font-['Plus_Jakarta_Sans'] font-bold tracking-tight text-on-surface">
                        Curated Feed
                    </h2>
                </div>

                {{-- Public / Friends toggle --}}
                <div class="bg-surface-container p-1 rounded-full flex items-center">
                    <a href="{{ route('activemember.feed', ['filter' => 'public']) }}"
                       class="px-5 py-2 rounded-full text-sm font-bold transition-all
                              {{ $filter === 'public'
                                  ? 'bg-surface-container-lowest text-primary shadow-sm'
                                  : 'text-on-surface-variant hover:text-primary' }}">
                        Public
                    </a>
                    <a href="{{ route('activemember.feed', ['filter' => 'friends']) }}"
                       class="px-5 py-2 rounded-full text-sm font-bold transition-all
                              {{ $filter === 'friends'
                                  ? 'bg-surface-container-lowest text-primary shadow-sm'
                                  : 'text-on-surface-variant hover:text-primary' }}">
                        Friends Only
                    </a>
                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════════════
             POSTS FEED
        ══════════════════════════════════════════════════════════════════ --}}
        @if($posts->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <span class="material-symbols-outlined text-5xl text-outline mb-4">feed</span>
                <h3 class="font-['Plus_Jakarta_Sans'] font-bold text-2xl text-on-surface mb-2">Nothing here yet</h3>
                <p class="text-sm text-on-surface-variant">
                    {{ $filter === 'friends' ? 'No posts from your friends yet.' : 'No approved posts to display.' }}
                </p>
                <a href="{{ route('activemember.posts.create') }}"
                   class="mt-6 px-6 py-3 rounded-full bg-primary text-on-primary font-bold text-sm hover:bg-primary-dim transition-colors">
                    Create the first post
                </a>
            </div>
        @else

            <div class="space-y-16">
                @foreach($posts as $post)

                    <article class="relative group" id="post-{{ $post->id }}">
                        <div class="space-y-6">

                            {{-- ── Post meta header ──────────────────────────── --}}
                            <div class="flex items-center justify-between px-2">
                                <div class="flex items-center gap-3">
                                    {{-- Type label with color by type --}}
                                    <span class="font-['Manrope'] text-[11px] font-bold uppercase tracking-[0.2em]
                                                 {{ $post->type === 'image' ? 'text-primary' : '' }}
                                                 {{ $post->type === 'audio' ? 'text-tertiary' : '' }}
                                                 {{ $post->type === 'video' ? 'text-secondary' : '' }}
                                                 {{ $post->type === 'text'  ? 'text-on-surface-variant' : '' }}">
                                        {{ $post->section?->name ?? $post->typeLabel() }}
                                    </span>
                                    <span class="w-1 h-1 bg-outline-variant rounded-full"></span>
                                    <span class="text-xs text-on-surface-variant font-medium">
                                        {{ $post->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                {{-- Report button --}}
                                <button onclick="openReportModal({{ $post->id }})"
                                        class="text-on-surface-variant hover:text-error transition-colors flex items-center gap-1 text-[11px] font-bold uppercase tracking-tighter">
                                    <span class="material-symbols-outlined text-sm">flag</span>
                                    Report
                                </button>
                            </div>

                            {{-- ── IMAGE POST ─────────────────────────────────── --}}
                            @if($post->type === 'image')
                                <div class="relative overflow-hidden rounded-3xl bg-surface-container-low aspect-[4/5]">
                                    <img src="{{ $post->mediaUrl() }}"
                                         alt="{{ $post->body ?? 'Post image' }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"/>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>

                            {{-- ── AUDIO POST ─────────────────────────────────── --}}
                            @elseif($post->type === 'audio')
                                <div class="bg-gradient-to-br from-primary to-primary-container rounded-[40px] p-8 aspect-video flex flex-col justify-between shadow-[0_30px_60px_-12px_rgba(2,83,205,0.2)]">
                                    <div class="flex justify-between items-start">
                                        <span class="material-symbols-outlined text-white/50 text-4xl">equalizer</span>
                                        <div class="text-right">
                                            <p class="text-white/70 text-[10px] font-bold uppercase tracking-[0.3em]">
                                                {{ $post->section?->name ?? 'Audio' }}
                                            </p>
                                            <p class="text-white font-['Plus_Jakarta_Sans'] font-bold">
                                                {{ $post->media_filename ?? 'Audio Track' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="h-1.5 w-full bg-white/20 rounded-full overflow-hidden">
                                            <div class="h-full w-0 bg-white rounded-full" id="progress-{{ $post->id }}"></div>
                                        </div>
                                        <div class="flex justify-center">
                                            <button onclick="toggleAudio({{ $post->id }}, '{{ $post->mediaUrl() }}')"
                                                    id="play-btn-{{ $post->id }}"
                                                    class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-primary shadow-xl active:scale-90 transition-transform">
                                                <span class="material-symbols-outlined text-3xl" id="play-icon-{{ $post->id }}" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            {{-- ── VIDEO POST ─────────────────────────────────── --}}
                            @elseif($post->type === 'video')
                                <div class="relative overflow-hidden rounded-3xl bg-surface-container aspect-video">
                                    @if($post->thumbnailUrl())
                                        <img src="{{ $post->thumbnailUrl() }}" alt="Video thumbnail"
                                             class="w-full h-full object-cover"/>
                                    @else
                                        <div class="w-full h-full bg-inverse-surface flex items-center justify-center">
                                            <span class="material-symbols-outlined text-white/20 text-6xl">videocam</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                        <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/30">
                                            <span class="material-symbols-outlined text-white text-4xl" style="font-variation-settings: 'FILL' 1;">play_circle</span>
                                        </div>
                                    </div>
                                    @if($post->media_duration)
                                        <div class="absolute bottom-4 left-4 right-4 flex justify-between items-center px-4 py-2 bg-black/40 backdrop-blur-md rounded-2xl">
                                            <span class="text-white text-xs font-bold">{{ $post->formattedDuration() }}</span>
                                            <span class="material-symbols-outlined text-white text-sm">fullscreen</span>
                                        </div>
                                    @endif
                                </div>

                            {{-- ── TEXT POST ──────────────────────────────────── --}}
                            @elseif($post->type === 'text')
                                <div class="bg-surface-container-low rounded-3xl p-8">
                                    <span class="material-symbols-outlined text-primary/20 text-5xl mb-3 block">format_quote</span>
                                    <p class="font-['Plus_Jakarta_Sans'] font-bold text-xl text-on-surface leading-relaxed">
                                        "{{ $post->body }}"
                                    </p>
                                </div>
                            @endif

                            {{-- ── Post body text (for non-text posts) ───────── --}}
                            @if($post->type !== 'text' && $post->body)
                                <div class="px-2 space-y-3">
                                    <h3 class="text-2xl font-['Plus_Jakarta_Sans'] font-bold leading-tight tracking-tight">
                                        {{ $post->body }}
                                    </h3>
                                </div>
                            @endif

                            {{-- ── Author + Like + Comment ─────────────────────── --}}
                            <div class="px-2 space-y-4">

                                {{-- Author --}}
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center shrink-0">
                                        <span class="font-bold text-xs text-primary">
                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-bold text-on-surface">{{ $post->user->name }}</span>
                                </div>

                                {{-- Like + Comment counts --}}
                                <div class="flex items-center gap-6">
                                    {{-- Like button --}}
                                    <form method="POST"
                                          action="{{ route('activemember.feed.like', $post) }}"
                                          class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="flex items-center gap-2 transition-all active:scale-95
                                                       {{ $post->isLikedBy(auth()->id())
                                                           ? 'text-primary bg-primary/10 px-4 py-2 rounded-full'
                                                           : 'text-on-surface-variant hover:text-primary' }}">
                                            <span class="material-symbols-outlined"
                                                  style="{{ $post->isLikedBy(auth()->id()) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">
                                                favorite
                                            </span>
                                            <span class="text-sm font-bold">
                                                {{ number_format($post->likes_count) }}
                                            </span>
                                        </button>
                                    </form>

                                    {{-- Comment toggle --}}
                                    <button onclick="toggleComments({{ $post->id }})"
                                            class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined">chat_bubble</span>
                                        <span class="text-sm font-bold">{{ number_format($post->comments_count) }}</span>
                                    </button>
                                </div>
                            </div>

                            {{-- ── Comments section ────────────────────────────── --}}
                            <div id="comments-{{ $post->id }}" class="hidden">
                                <div class="bg-surface-container-low rounded-[32px] p-6 space-y-4">

                                    {{-- Existing comments --}}
                                    @foreach($post->comments->take(3) as $comment)
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center shrink-0">
                                                <span class="font-bold text-xs text-on-surface-variant">
                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-on-surface font-semibold leading-none mb-0.5">
                                                    {{ $comment->user->name }}
                                                </p>
                                                <p class="text-xs text-on-surface-variant truncate">
                                                    {{ $comment->body }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Quick reply chips --}}
                                    <form method="POST"
                                          action="{{ route('activemember.feed.comment', $post) }}"
                                          id="comment-form-{{ $post->id }}">
                                        @csrf
                                        <input type="hidden" name="body" id="comment-body-{{ $post->id }}"/>
                                        <div class="flex gap-2 pt-2 overflow-x-auto pb-1 no-scrollbar">
                                            @foreach(['Stunning!', 'Love this!', 'Inspirational', 'Amazing work'] as $chip)
                                                <button type="button"
                                                        onclick="submitQuickComment({{ $post->id }}, '{{ $chip }}')"
                                                        class="whitespace-nowrap px-4 py-2 bg-white rounded-full text-[11px] font-bold text-primary border border-primary/10 hover:bg-primary hover:text-white transition-all">
                                                    {{ $chip }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </form>

                                    {{-- Full comment input --}}
                                    <form method="POST" action="{{ route('activemember.feed.comment', $post) }}" class="flex gap-2 pt-1">
                                        @csrf
                                        <input type="text"
                                               name="body"
                                               placeholder="Write a comment..."
                                               required
                                               class="flex-1 bg-surface-container rounded-full px-4 py-2.5 text-sm border-none focus:ring-2 focus:ring-primary/20 font-['Manrope']"/>
                                        <button type="submit"
                                                class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center shrink-0 hover:bg-primary-dim transition-colors">
                                            <span class="material-symbols-outlined text-lg">send</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </article>

                @endforeach
            </div>

            {{-- Pagination --}}
            @if($posts->hasPages())
                <div class="flex justify-center pt-4">
                    {{ $posts->links() }}
                </div>
            @endif

        @endif
    </main>

    {{-- FAB create button --}}
    <a href="{{ route('activemember.posts.create') }}"
       class="fixed bottom-24 right-6 w-14 h-14 bg-gradient-to-br from-primary to-primary-container text-white rounded-full flex items-center justify-center shadow-[0_20px_40px_rgba(2,83,205,0.3)] z-40 active:scale-95 transition-transform hover:scale-110">
        <span class="material-symbols-outlined text-3xl">add</span>
    </a>


    {{-- ════════════════════════════════════════════════════════
         REPORT MODAL
    ════════════════════════════════════════════════════════ --}}
    <div id="modal-report"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         role="dialog" aria-modal="true">

        <div class="absolute inset-0 bg-inverse-surface/30 backdrop-blur-sm" onclick="closeReportModal()"></div>

        <div class="relative w-full max-w-md bg-surface-container-lowest rounded-2xl shadow-2xl overflow-hidden animate-modal">

            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-outline-variant/10">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-primary font-extrabold mb-0.5">Moderation</p>
                    <h2 class="font-['Plus_Jakarta_Sans'] text-xl font-extrabold text-on-surface">Report Post</h2>
                </div>
                <button onclick="closeReportModal()"
                        class="w-9 h-9 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="form-report" method="POST" action="" class="px-8 py-7 space-y-5">
                @csrf

                {{-- Reason --}}
                <div class="space-y-2">
                    <label class="block text-[10px] uppercase tracking-[0.12em] font-bold text-on-surface-variant ml-1">
                        Reason <span class="text-error">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            ['spam',              'Spam'],
                            ['harassment',        'Harassment'],
                            ['inappropriate',     'Inappropriate'],
                            ['false_information', 'False Information'],
                            ['other',             'Other'],
                        ] as [$value, $label])
                            <label class="flex items-center gap-2 p-3 rounded-xl bg-surface-container cursor-pointer border-2 border-transparent has-[:checked]:border-primary transition-all">
                                <input type="radio" name="reason" value="{{ $value }}"
                                       class="text-primary border-outline focus:ring-primary w-4 h-4"/>
                                <span class="text-sm font-semibold text-on-surface">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Extra details --}}
                <div class="space-y-2">
                    <label for="report-details"
                           class="block text-[10px] uppercase tracking-[0.12em] font-bold text-on-surface-variant ml-1">
                        Additional Details <span class="text-on-surface-variant font-normal">(optional)</span>
                    </label>
                    <textarea id="report-details" name="details" rows="3"
                              placeholder="Describe the issue..."
                              class="w-full px-4 py-3 rounded-xl bg-surface-container-high border-transparent focus:border-primary/20 focus:ring-0 text-on-surface placeholder:text-outline/40 transition-all font-medium text-sm resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closeReportModal()"
                            class="flex-1 py-3.5 rounded-full border border-outline-variant/30 text-on-surface-variant font-bold text-sm hover:bg-surface-container transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 py-3.5 rounded-full bg-error text-on-error font-bold text-sm hover:opacity-90 active:scale-95 transition-all shadow-md shadow-error/20">
                        Submit Report
                    </button>
                </div>
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

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@push('scripts')
<script>
    // ── Report modal ──────────────────────────────────────────────────────────
    function openReportModal(postId) {
        document.getElementById('form-report').action = `/feed/${postId}/report`;
        document.getElementById('modal-report').classList.add('modal-open');
        document.body.classList.add('modal-active');
    }
    function closeReportModal() {
        document.getElementById('modal-report').classList.remove('modal-open');
        document.body.classList.remove('modal-active');
    }

    // ── Toggle comments section ───────────────────────────────────────────────
    function toggleComments(postId) {
        const el = document.getElementById('comments-' + postId);
        el.classList.toggle('hidden');
    }

    // ── Quick comment chip submit ─────────────────────────────────────────────
    function submitQuickComment(postId, text) {
        document.getElementById('comment-body-' + postId).value = text;
        document.getElementById('comment-form-' + postId).submit();
    }

    // ── Simple audio player ───────────────────────────────────────────────────
    const audioPlayers = {};

    function toggleAudio(postId, url) {
        if (!audioPlayers[postId]) {
            audioPlayers[postId] = new Audio(url);
            audioPlayers[postId].addEventListener('timeupdate', () => {
                const pct = (audioPlayers[postId].currentTime / audioPlayers[postId].duration) * 100;
                const bar = document.getElementById('progress-' + postId);
                if (bar) bar.style.width = pct + '%';
            });
            audioPlayers[postId].addEventListener('ended', () => {
                const icon = document.getElementById('play-icon-' + postId);
                if (icon) icon.textContent = 'play_arrow';
                const bar = document.getElementById('progress-' + postId);
                if (bar) bar.style.width = '0%';
            });
        }

        const player = audioPlayers[postId];
        const icon   = document.getElementById('play-icon-' + postId);

        if (player.paused) {
            // Pause all other players
            Object.entries(audioPlayers).forEach(([id, p]) => {
                if (id != postId && !p.paused) {
                    p.pause();
                    const otherIcon = document.getElementById('play-icon-' + id);
                    if (otherIcon) otherIcon.textContent = 'play_arrow';
                }
            });
            player.play();
            icon.textContent = 'pause';
        } else {
            player.pause();
            icon.textContent = 'play_arrow';
        }
    }

    // ── ESC to close modals ───────────────────────────────────────────────────
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeReportModal();
    });

    // ── Auto-dismiss flash ────────────────────────────────────────────────────
    ['flash-success', 'flash-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) setTimeout(() => el.remove(), 4000);
    });
</script>
@endpush