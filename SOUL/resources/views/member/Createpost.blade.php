@extends('layouts.member')

@section('title', 'Create Post - Fluid Curator')

@section('content')




    <main class="max-w-4xl mx-auto px-6 pt-24 pb-32">

        {{-- ── Flash messages ──────────────────────────────────────────────── --}}
        @if(session('success'))
            <div id="flash-success"
                 class="mb-8 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3.5 rounded-xl text-sm font-semibold">
                <span class="material-symbols-outlined text-green-500 text-lg">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- ── PAGE HEADER ──────────────────────────────────────────────────── --}}
        <div class="mb-12">
            <span class="text-[11px] font-semibold uppercase tracking-widest text-primary mb-2 block font-['Manrope']">
                Content Studio
            </span>
            <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface leading-tight font-['Plus_Jakarta_Sans']">
                Create a new <span class="text-primary italic">masterpiece</span>.
            </h2>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             COMPOSER FORM
        ══════════════════════════════════════════════════════════════════ --}}
        <form method="POST"
              action="{{ route('activemember.posts.store') }}"
              enctype="multipart/form-data"
              id="post-form"
              class="grid grid-cols-1 md:grid-cols-12 gap-8">
            @csrf

            {{-- Hidden: resolved post type --}}
            <input type="hidden" name="type" id="post-type" value="text"/>

            {{-- ── LEFT: Main Content Area ─────────────────────────────────── --}}
            <div class="md:col-span-8 space-y-6">

                {{-- Text + media canvas --}}
                <div class="bg-surface-container-lowest p-8 rounded-[32px] shadow-[0_20px_40px_rgba(39,44,81,0.04)]">

                    {{-- Text body --}}
                    <textarea
                        name="body"
                        id="body"
                        class="w-full min-h-[200px] bg-transparent border-none focus:ring-0 text-xl font-medium text-on-surface placeholder:text-outline/50 resize-none font-['Manrope']"
                        placeholder="What's your story today?"
                    >{{ old('body') }}</textarea>

                    @error('body')
                        <p class="text-xs text-error mt-1">{{ $message }}</p>
                    @enderror

                    {{-- Media preview / drop zones --}}
                    <div class="mt-8 grid grid-cols-2 gap-4" id="media-grid">

                        {{-- Image drop zone --}}
                        <div id="zone-image"
                             onclick="triggerUpload('image')"
                             class="aspect-square rounded-2xl bg-surface-container-low flex flex-col items-center justify-center border-2 border-dashed border-outline-variant/30 group hover:border-primary/40 transition-all cursor-pointer relative overflow-hidden">
                            <img id="preview-image" class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl" alt="Image preview"/>
                            <span class="material-symbols-outlined text-4xl text-outline-variant group-hover:text-primary transition-colors" id="icon-image">image</span>
                            <span class="text-xs font-bold uppercase tracking-tighter text-outline mt-2" id="label-image">Add Image</span>
                        </div>

                        <div class="grid grid-rows-2 gap-4">
                            {{-- Video drop zone --}}
                            <div id="zone-video"
                                 onclick="triggerUpload('video')"
                                 class="rounded-2xl bg-surface-container-low flex items-center justify-center border-2 border-dashed border-outline-variant/30 group hover:border-primary/40 transition-all cursor-pointer relative overflow-hidden">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="material-symbols-outlined text-2xl text-outline-variant group-hover:text-primary transition-colors" id="icon-video">videocam</span>
                                    <span class="text-[10px] font-bold text-outline hidden" id="label-video">Video</span>
                                </div>
                            </div>

                            {{-- Audio drop zone --}}
                            <div id="zone-audio"
                                 onclick="triggerUpload('audio')"
                                 class="rounded-2xl bg-surface-container-low flex items-center justify-center border-2 border-dashed border-outline-variant/30 group hover:border-primary/40 transition-all cursor-pointer relative overflow-hidden">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="material-symbols-outlined text-2xl text-outline-variant group-hover:text-primary transition-colors" id="icon-audio">mic</span>
                                    <span class="text-[10px] font-bold text-outline hidden" id="label-audio">Audio</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hidden file inputs --}}
                    <input id="input-image" name="media" type="file"
                           accept="image/jpeg,image/png,image/webp,image/gif"
                           class="hidden" onchange="handleFileSelect(this, 'image')"/>
                    <input id="input-video" name="media" type="file"
                           accept="video/mp4,video/mov,video/avi,video/webm"
                           class="hidden" onchange="handleFileSelect(this, 'video')"/>
                    <input id="input-audio" name="media" type="file"
                           accept="audio/mpeg,audio/wav,audio/ogg,audio/mp4,audio/aac"
                           class="hidden" onchange="handleFileSelect(this, 'audio')"/>

                    @error('media')
                        <p class="text-xs text-error mt-3">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Media action bar --}}
                <div class="flex flex-wrap gap-3">
                    <button type="button"
                            onclick="activateType('image')"
                            id="btn-image"
                            class="media-btn flex items-center gap-2 px-5 py-3 rounded-full font-bold text-sm transition-all bg-secondary-fixed text-on-secondary-fixed hover:brightness-95">
                        <span class="material-symbols-outlined text-lg">add_a_photo</span>
                        Images
                    </button>
                    <button type="button"
                            onclick="activateType('video')"
                            id="btn-video"
                            class="media-btn flex items-center gap-2 px-5 py-3 rounded-full font-bold text-sm transition-all bg-surface-container-high text-on-surface hover:bg-surface-container-highest">
                        <span class="material-symbols-outlined text-lg">movie</span>
                        Videos
                    </button>
                    <button type="button"
                            onclick="activateType('audio')"
                            id="btn-audio"
                            class="media-btn flex items-center gap-2 px-5 py-3 rounded-full font-bold text-sm transition-all bg-surface-container-high text-on-surface hover:bg-surface-container-highest">
                        <span class="material-symbols-outlined text-lg">graphic_eq</span>
                        Audio
                    </button>
                    <button type="button"
                            onclick="activateType('text')"
                            id="btn-text"
                            class="media-btn flex items-center gap-2 px-5 py-3 rounded-full font-bold text-sm transition-all bg-surface-container-high text-on-surface hover:bg-surface-container-highest">
                        <span class="material-symbols-outlined text-lg">notes</span>
                        Text Only
                    </button>
                </div>

            </div>

            {{-- ── RIGHT: Sidebar config ────────────────────────────────────── --}}
            <div class="md:col-span-4 space-y-6">

                {{-- Privacy Controls --}}
                <div class="bg-surface-container p-6 rounded-[24px]">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface/60 mb-5 font-['Manrope']">
                        Privacy Controls
                    </h3>
                    <div class="space-y-3">

                        <label class="flex items-center justify-between p-4 rounded-2xl bg-surface-container-lowest cursor-pointer border-2 border-transparent has-[:checked]:border-primary transition-all">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">public</span>
                                <div>
                                    <p class="text-sm font-bold text-on-surface">Public</p>
                                    <p class="text-[11px] text-on-surface-variant">Visible to everyone</p>
                                </div>
                            </div>
                            <input checked
                                   class="w-5 h-5 text-primary border-outline focus:ring-primary"
                                   name="privacy" type="radio" value="public"/>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-2xl bg-surface-container-lowest cursor-pointer border-2 border-transparent has-[:checked]:border-primary transition-all">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">group</span>
                                <div>
                                    <p class="text-sm font-bold text-on-surface">Friends Only</p>
                                    <p class="text-[11px] text-on-surface-variant">Only your inner circle</p>
                                </div>
                            </div>
                            <input class="w-5 h-5 text-primary border-outline focus:ring-primary"
                                   name="privacy" type="radio" value="friends"/>
                        </label>

                        @error('privacy')
                            <p class="text-xs text-error ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Allow Duets toggle --}}
                <div class="bg-surface-container-lowest border border-outline-variant/10 p-6 rounded-[24px]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-tertiary-container/30 flex items-center justify-center">
                                <span class="material-symbols-outlined text-tertiary">celebration</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-on-surface">Allow Duets</p>
                                <p class="text-[11px] text-on-surface-variant">Let others remix content</p>
                            </div>
                        </div>
                        {{-- Toggle button wired to hidden checkbox --}}
                        <button type="button"
                                id="duets-toggle"
                                onclick="toggleDuets()"
                                class="w-12 h-6 rounded-full relative flex items-center px-1 transition-colors bg-primary">
                            <div id="duets-dot" class="w-4 h-4 bg-white rounded-full ml-auto transition-all"></div>
                        </button>
                        <input type="hidden" name="allow_duets" id="allow-duets-input" value="1"/>
                    </div>
                </div>

                {{-- Section info --}}
                @if($section)
                    <div class="bg-surface-container-low p-4 rounded-2xl flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">folder_shared</span>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Posting to</p>
                            <p class="text-sm font-bold text-on-surface">{{ $section->name }}</p>
                        </div>
                    </div>
                @endif

                {{-- Publish --}}
                <button type="submit"
                        class="w-full py-5 rounded-full bg-gradient-to-br from-primary to-primary-container text-white font-extrabold text-lg shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Publish Post
                </button>

                {{-- Save as draft (future feature) --}}
                <button type="button"
                        class="w-full py-4 rounded-full text-primary font-bold text-sm hover:bg-surface-container transition-all">
                    Save as Draft
                </button>

            </div>
        </form>
    </main>

@endsection

@push('scripts')
<script>
    // ── Active media type ─────────────────────────────────────────────────────
    let activeType = 'text';

    const typeColors = {
        active:   'bg-primary text-on-primary shadow-md shadow-primary/20',
        inactive: 'bg-surface-container-high text-on-surface hover:bg-surface-container-highest',
    };

    function activateType(type) {
        activeType = type;
        document.getElementById('post-type').value = type;

        // Reset all buttons
        ['image', 'video', 'audio', 'text'].forEach(t => {
            const btn = document.getElementById('btn-' + t);
            btn.className = btn.className
                .replace('bg-primary text-on-primary shadow-md shadow-primary/20', '')
                .replace('bg-secondary-fixed text-on-secondary-fixed', '')
                .trim();
            btn.classList.add('bg-surface-container-high', 'text-on-surface', 'hover:bg-surface-container-highest');
        });

        // Highlight selected
        const selected = document.getElementById('btn-' + type);
        selected.classList.remove('bg-surface-container-high', 'text-on-surface', 'hover:bg-surface-container-highest');
        selected.classList.add('bg-primary', 'text-on-primary', 'shadow-md', 'shadow-primary/20');

        // Trigger file picker for media types
        if (type !== 'text') {
            triggerUpload(type);
        }
    }

    // ── File input trigger ────────────────────────────────────────────────────
    function triggerUpload(type) {
        // Disable all other inputs first
        ['image', 'video', 'audio'].forEach(t => {
            document.getElementById('input-' + t).disabled = (t !== type);
        });
        document.getElementById('input-' + type).click();
    }

    // ── Handle file selection & preview ──────────────────────────────────────
    function handleFileSelect(input, type) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        activeType = type;
        document.getElementById('post-type').value = type;

        if (type === 'image') {
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('preview-image');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                document.getElementById('icon-image').classList.add('hidden');
                document.getElementById('label-image').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        if (type === 'video') {
            document.getElementById('icon-video').textContent  = 'check_circle';
            document.getElementById('icon-video').classList.add('text-primary');
            document.getElementById('label-video').textContent = file.name.substring(0, 12) + '...';
            document.getElementById('label-video').classList.remove('hidden');
        }

        if (type === 'audio') {
            document.getElementById('icon-audio').textContent  = 'check_circle';
            document.getElementById('icon-audio').classList.add('text-primary');
            document.getElementById('label-audio').textContent = file.name.substring(0, 12) + '...';
            document.getElementById('label-audio').classList.remove('hidden');
        }

        // Highlight the zone border
        ['image', 'video', 'audio'].forEach(t => {
            const zone = document.getElementById('zone-' + t);
            zone.classList.toggle('border-primary', t === type);
            zone.classList.toggle('border-outline-variant/30', t !== type);
        });
    }

    // ── Duets toggle ──────────────────────────────────────────────────────────
    let duetsEnabled = true;

    function toggleDuets() {
        duetsEnabled = !duetsEnabled;
        const toggle = document.getElementById('duets-toggle');
        const dot    = document.getElementById('duets-dot');
        const input  = document.getElementById('allow-duets-input');

        if (duetsEnabled) {
            toggle.classList.replace('bg-surface-container-high', 'bg-primary');
            dot.classList.add('ml-auto');
            dot.classList.remove('mr-auto');
            input.value = '1';
        } else {
            toggle.classList.replace('bg-primary', 'bg-surface-container-high');
            dot.classList.remove('ml-auto');
            dot.classList.add('mr-auto');
            input.value = '0';
        }
    }

    // ── Auto-dismiss flash ────────────────────────────────────────────────────
    const flash = document.getElementById('flash-success');
    if (flash) setTimeout(() => flash.remove(), 4000);
</script>
@endpush