@extends('layouts.admin')

@section('title', 'Member Posts - ' . $section->name)
@section('breadcrumb', 'Moderation Queue')
@section('page-title', 'Member Posts')

@php $activePage = 'posts'; @endphp

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
    <div class="mb-10 flex flex-col xl:flex-row xl:items-end justify-between gap-6">
        <div>
            <h2 class="font-['Plus_Jakarta_Sans'] text-4xl xl:text-5xl font-extrabold tracking-tight text-on-surface">
                Member Posts
            </h2>
            <p class="text-on-surface-variant mt-1 text-sm">
                Section: <span class="font-bold text-primary">{{ $section->name }}</span>
                — you can only moderate posts within your section.
            </p>
        </div>

        {{-- Type filter pills --}}
        <form method="GET" action="{{ route('admin.posts') }}"
              id="type-filter-form"
              class="flex items-center gap-2 flex-wrap shrink-0">
            @foreach(['' => 'All ('.$counts['all'].')', 'image' => 'Images ('.$counts['image'].')', 'video' => 'Videos ('.$counts['video'].')', 'audio' => 'Audio ('.$counts['audio'].')', 'text' => 'Text ('.$counts['text'].')'] as $value => $label)
                <button type="submit" name="type" value="{{ $value }}"
                        class="px-5 py-2.5 rounded-full font-bold text-sm transition-all
                               {{ $typeFilter === $value
                                   ? 'bg-primary text-on-primary shadow-md shadow-primary/20'
                                   : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                    {{ $label }}
                </button>
            @endforeach
        </form>
    </div>

    {{-- ── POSTS BENTO GRID ────────────────────────────────────────────────── --}}
    @if($posts->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <span class="material-symbols-outlined text-5xl text-outline mb-4">inbox</span>
            <h3 class="font-['Plus_Jakarta_Sans'] font-bold text-2xl text-on-surface mb-2">No posts found</h3>
            <p class="text-on-surface-variant text-sm">
                {{ $typeFilter ? 'No ' . $typeFilter . ' posts in your section yet.' : 'No posts in your section yet.' }}
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

            @foreach($posts as $index => $post)

                {{-- ── IMAGE POST ───────────────────────────────────────────── --}}
                @if($post->type === 'image')

                    {{-- First image post = featured large card --}}
                    @if($index === 0 && $typeFilter === '' || $typeFilter === 'image')
                        <article class="md:col-span-8 bg-surface-container-lowest rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                            <div class="relative h-[400px] w-full bg-surface-container">
                                <img
                                    src="{{ $post->mediaUrl() }}"
                                    alt="{{ $post->body ?? 'Post image' }}"
                                    class="w-full h-full object-cover"
                                />
                                <div class="absolute top-4 left-4">
                                    <span class="backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $post->typeBadgeClass() }}">
                                        {{ $post->typeLabel() }}
                                    </span>
                                </div>
                                {{-- Status badge --}}
                                @if($post->status === 'pending')
                                    <div class="absolute top-4 right-4 bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                                        Pending Review
                                    </div>
                                @endif
                            </div>
                            <div class="p-8">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center border-2 border-primary-container">
                                            <span class="font-['Plus_Jakarta_Sans'] font-extrabold text-lg text-primary">
                                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-lg leading-none">{{ $post->user->name }}</h3>
                                            <p class="text-sm text-outline font-medium">{{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        @if($post->status === 'pending')
                                            <form method="POST" action="{{ route('dashboard.posts.approve', $post) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="bg-primary/10 hover:bg-primary text-primary hover:text-on-primary font-bold py-2.5 px-5 rounded-full transition-all text-sm">
                                                    Approve
                                                </button>
                                            </form>
                                        @endif
                                        <button onclick="openDeleteModal({{ $post->id }})"
                                                class="bg-error-container/10 hover:bg-error-container text-error hover:text-white font-bold py-2.5 px-5 rounded-full transition-all flex items-center gap-2 text-sm">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                @if($post->body)
                                    <p class="text-base text-on-surface leading-relaxed font-medium">{{ $post->body }}</p>
                                @endif
                            </div>
                        </article>
                    @else
                        <article class="md:col-span-4 bg-surface-container-lowest rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                            <div class="relative h-56 w-full bg-surface-container">
                                <img src="{{ $post->mediaUrl() }}" alt="{{ $post->body ?? 'Post image' }}"
                                     class="w-full h-full object-cover"/>
                                <div class="absolute top-3 left-3">
                                    <span class="backdrop-blur-md px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $post->typeBadgeClass() }}">
                                        {{ $post->typeLabel() }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center">
                                            <span class="font-bold text-sm text-primary">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm leading-none">{{ $post->user->name }}</p>
                                            <p class="text-xs text-outline">{{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <button onclick="openDeleteModal({{ $post->id }})" class="text-error hover:text-error-dim transition-colors">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                                @if($post->body)
                                    <p class="text-sm text-on-surface line-clamp-2">{{ $post->body }}</p>
                                @endif
                            </div>
                        </article>
                    @endif

                {{-- ── VIDEO POST ───────────────────────────────────────────── --}}
                @elseif($post->type === 'video')
                    <article class="md:col-span-5 bg-surface-container-lowest rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="relative aspect-video bg-inverse-surface flex items-center justify-center">
                            @if($post->thumbnailUrl())
                                <img src="{{ $post->thumbnailUrl() }}" alt="Video thumbnail"
                                     class="w-full h-full object-cover opacity-70"/>
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-inverse-surface to-outline flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-5xl opacity-30">videocam</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                                    <span class="material-symbols-outlined text-white text-4xl" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                                </div>
                            </div>
                            @if($post->media_duration)
                                <div class="absolute bottom-3 left-3">
                                    <span class="text-white text-[10px] font-bold bg-black/50 px-2 py-0.5 rounded">
                                        {{ $post->formattedDuration() }}
                                    </span>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $post->typeBadgeClass() }}">
                                    {{ $post->typeLabel() }}
                                </span>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center shrink-0">
                                    <span class="font-bold text-sm text-primary">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-sm leading-none">{{ $post->user->name }}</h3>
                                    <p class="text-[11px] text-outline">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                                <button onclick="openDeleteModal({{ $post->id }})" class="text-error shrink-0">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                            @if($post->body)
                                <p class="text-sm text-on-surface line-clamp-2">{{ $post->body }}</p>
                            @endif
                        </div>
                    </article>

                {{-- ── AUDIO POST ───────────────────────────────────────────── --}}
                @elseif($post->type === 'audio')
                    <article class="md:col-span-4 bg-surface-container-low rounded-3xl p-6 border border-outline-variant/10">
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center shrink-0">
                                    <span class="font-bold text-sm text-primary">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm leading-none">{{ $post->user->name }}</h3>
                                    <p class="text-xs text-outline">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <button onclick="openDeleteModal({{ $post->id }})" class="text-error">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </div>

                        {{-- Audio player --}}
                        <div class="bg-surface-container rounded-2xl p-4 mb-4 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-tertiary-container flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-tertiary text-2xl" style="font-variation-settings: 'FILL' 1;">music_note</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm truncate text-on-surface">
                                    {{ $post->media_filename ?? 'Audio file' }}
                                </p>
                                <p class="text-xs text-on-surface-variant">
                                    {{ $post->formattedDuration() ?: '—' }}
                                    @if($post->media_size)
                                        · {{ $post->formattedSize() }}
                                    @endif
                                </p>
                            </div>
                            <span class="material-symbols-outlined text-primary text-2xl">graphic_eq</span>
                        </div>

                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider mb-3 {{ $post->typeBadgeClass() }}">
                            {{ $post->typeLabel() }}
                        </span>

                        @if($post->body)
                            <p class="text-on-surface text-sm leading-relaxed">{{ Str::limit($post->body, 120) }}</p>
                        @endif
                    </article>

                {{-- ── TEXT POST ────────────────────────────────────────────── --}}
                @elseif($post->type === 'text')
                    <article class="md:col-span-4 bg-surface-container-low rounded-3xl p-8 flex flex-col justify-between border border-outline-variant/10">
                        <div>
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center">
                                    <span class="font-bold text-sm text-primary">{{ strtoupper(substr($post->user->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm leading-none">{{ $post->user->name }}</h3>
                                    <p class="text-xs text-outline">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="inline-block px-3 py-1 rounded-full font-bold uppercase tracking-wider text-[10px] mb-4 {{ $post->typeBadgeClass() }}">
                                {{ $post->typeLabel() }}
                            </span>
                            <p class="text-on-surface leading-relaxed text-sm line-clamp-5">
                                "{{ $post->body }}"
                            </p>
                        </div>
                        <div class="mt-6 pt-5 border-t border-outline-variant/20 flex justify-between items-center">
                            @if($post->status === 'pending')
                                <form method="POST" action="{{ route('dashboard.posts.approve', $post) }}">
                                    @csrf
                                    <button type="submit" class="text-primary font-bold text-sm hover:underline">
                                        Approve
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-on-surface-variant font-medium capitalize">{{ $post->status }}</span>
                            @endif
                            <button onclick="openDeleteModal({{ $post->id }})"
                                    class="text-error font-bold text-sm flex items-center gap-1 hover:underline">
                                <span class="material-symbols-outlined text-base">delete</span>
                                Delete Post
                            </button>
                        </div>
                    </article>
                @endif

            @endforeach

        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $posts->links() }}
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
                    This post will be permanently removed from your section. This action cannot be undone.
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

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-5 {
        display: -webkit-box;
        -webkit-line-clamp: 5;
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

    function openDeleteModal(postId) {
        $id('form-delete').action = `/dashboard/posts/${postId}`;
        $id('modal-delete').classList.add('modal-open');
        lockScroll();
    }
    function closeDeleteModal() {
        $id('modal-delete').classList.remove('modal-open');
        unlockScroll();
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeDeleteModal();
    });

    const flash = $id('flash-success');
    if (flash) setTimeout(() => flash.remove(), 4000);
</script>
@endpush