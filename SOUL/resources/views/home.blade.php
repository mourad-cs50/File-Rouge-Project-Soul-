@extends('layouts.app')

@section('title', 'The Curator | Share Your World')

@section('content')

    {{-- ===================== HERO SECTION ===================== --}}
    <section class="max-w-[1440px] mx-auto px-12 py-20 lg:py-32 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

        {{-- Left: Copy --}}
        <div class="flex flex-col gap-8">
            <div class="flex flex-col gap-4">
                <span class="font-['Manrope'] text-sm uppercase tracking-[0.2em] text-[#0253cd] font-bold">
                    Creative Authority
                </span>
                <h1 class="font-['Plus_Jakarta_Sans'] text-6xl lg:text-7xl font-extrabold tracking-tighter text-[#272c51] leading-[1.1]">
                    Share Your World in Every
                    <span class="text-transparent bg-clip-text hero-gradient">Format.</span>
                </h1>
            </div>

            <p class="font-['Manrope'] text-xl text-on-surface-variant leading-relaxed max-w-lg">
                The premium platform designed for those who value creative depth. Curate pictures, clips, sounds, and stories in a unified, high-end digital gallery.
            </p>

            <div class="flex items-center gap-4 pt-4">
                <button class="hero-gradient text-on-primary px-10 py-4 rounded-full font-bold text-lg editorial-shadow transition-transform hover:scale-105 active:scale-95">
                    <a href="{{ route('register') }}">Get Started</a>
                </button>
                <button class="bg-surface-container-high text-[#0253cd] px-10 py-4 rounded-full font-bold text-lg transition-transform hover:bg-surface-container-highest active:scale-95">
                    View Formats
                </button>
            </div>
        </div>

        {{-- Right: Visual Composition --}}
        <div class="relative">
            <div class="relative aspect-square rounded-[2rem] overflow-hidden editorial-shadow bg-surface-container">
                <img
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAbAKEtXMF5BG8uFyocvpiuIAi2qp3b-t052adr3Fxo2vy1JBCGBfvreNgHLPexTSDahOWu4uHSExikSWb6vRcxdSaeN73atsdc4AVdZvRZs9FiXnCLHJ9Gn7rnip1ltwgfS09IDNi04mNLq3cw-2xvWqa7R3yLEbJm7qKv9vPtPLu7VZJzIQnpg_1FCABkxBbEc3v66LE7_ETxNuWI_bCSmOISBuBGKQRqOTSZnnNNnE2CBTOvrj3AxQvtrXjb9DVAufcHBJIYLV7q"
                    alt="Modern abstract gallery representation"
                    class="w-full h-full object-cover opacity-90"
                />

                {{-- Floating Card: Upload Progress --}}
                <div class="absolute top-12 -left-8 bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-xl w-64 border border-white/20">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-[#0253cd] flex items-center justify-center text-white">
                            <span class="material-symbols-outlined">brush</span>
                        </div>
                        <div>
                            <div class="text-sm font-bold">Curated Motion</div>
                            <div class="text-[10px] text-slate-500">Processing high-res clip...</div>
                        </div>
                    </div>
                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-[#0253cd] w-[75%]"></div>
                    </div>
                </div>

                {{-- Floating Card: Analytics --}}
                <div class="absolute bottom-12 -right-8 bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-xl w-72 border border-white/20">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-400">
                            Narrative Analytics
                        </span>
                        <span class="material-symbols-outlined text-green-500">trending_up</span>
                    </div>
                    <div class="text-2xl font-extrabold text-[#0253cd]">14.2k</div>
                    <div class="text-xs text-slate-500">Readers engaged this week</div>
                </div>
            </div>

            {{-- Decorative blurs --}}
            <div class="absolute -z-10 -top-12 -right-12 w-64 h-64 bg-[#789dff]/10 rounded-full blur-3xl"></div>
            <div class="absolute -z-10 -bottom-12 -left-12 w-64 h-64 bg-[#0253cd]/10 rounded-full blur-3xl"></div>
        </div>

    </section>

    {{-- ===================== FOUR PILLARS SECTION ===================== --}}
    <section class="bg-surface-container-low py-24">
        <div class="max-w-[1440px] mx-auto px-12">

            {{-- Section Header --}}
            <div class="flex flex-col gap-2 mb-16 text-center max-w-2xl mx-auto">
                <span class="font-['Manrope'] text-sm uppercase tracking-[0.3em] text-[#0253cd] font-bold">
                    The Ecosystem
                </span>
                <h2 class="font-['Plus_Jakarta_Sans'] text-4xl lg:text-5xl font-extrabold tracking-tighter text-[#272c51]">
                    Our Four Core Pillars
                </h2>
                <p class="text-on-surface-variant mt-4">
                    Every medium deserves a dedicated space. We provide the architecture; you provide the inspiration.
                </p>
            </div>

            {{-- Pillar Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                {{-- Visuals --}}
                <div class="bg-surface-container-lowest p-10 rounded-[2rem] flex flex-col gap-6 group hover:translate-y-[-8px] transition-all duration-300 editorial-shadow">
                    <div class="w-16 h-16 rounded-2xl bg-secondary-container flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">image</span>
                    </div>
                    <div>
                        <h3 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold mb-3">Visuals</h3>
                        <p class="text-on-surface-variant font-['Manrope'] text-sm leading-relaxed">
                            High-fidelity picture galleries with granular metadata control and professional color grading support.
                        </p>
                    </div>
                </div>

                {{-- Motion --}}
                <div class="bg-surface-container-lowest p-10 rounded-[2rem] flex flex-col gap-6 group hover:translate-y-[-8px] transition-all duration-300 editorial-shadow">
                    <div class="w-16 h-16 rounded-2xl bg-secondary-container flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">movie</span>
                    </div>
                    <div>
                        <h3 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold mb-3">Motion</h3>
                        <p class="text-on-surface-variant font-['Manrope'] text-sm leading-relaxed">
                            Immersive video experiences that support cinematic aspect ratios and seamless playback across all devices.
                        </p>
                    </div>
                </div>

                {{-- Aural --}}
                <div class="bg-surface-container-lowest p-10 rounded-[2rem] flex flex-col gap-6 group hover:translate-y-[-8px] transition-all duration-300 editorial-shadow">
                    <div class="w-16 h-16 rounded-2xl bg-secondary-container flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">graphic_eq</span>
                    </div>
                    <div>
                        <h3 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold mb-3">Aural</h3>
                        <p class="text-on-surface-variant font-['Manrope'] text-sm leading-relaxed">
                            Sonic storytelling through high-fidelity audio formats, spatial soundscapes, and intuitive wave exploration.
                        </p>
                    </div>
                </div>

                {{-- Narrative --}}
                <div class="bg-surface-container-lowest p-10 rounded-[2rem] flex flex-col gap-6 group hover:translate-y-[-8px] transition-all duration-300 editorial-shadow">
                    <div class="w-16 h-16 rounded-2xl bg-secondary-container flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">auto_stories</span>
                    </div>
                    <div>
                        <h3 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold mb-3">Narrative</h3>
                        <p class="text-on-surface-variant font-['Manrope'] text-sm leading-relaxed">
                            Editorial-grade text tools designed for deep reading, long-form stories, and beautiful typography layouts.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== CTA SECTION ===================== --}}
    <section class="relative overflow-hidden">
        <div class="bg-[#272c51] py-28 px-12 text-center flex flex-col items-center gap-10">

            {{-- Background decorative gradient --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <div class="absolute top-0 left-0 w-full h-full hero-gradient" style="clip-path: circle(50% at 100% 0%);"></div>
            </div>

            {{-- Copy --}}
            <div class="relative z-10 flex flex-col gap-6 max-w-3xl">
                <h2 class="font-['Plus_Jakarta_Sans'] text-5xl lg:text-6xl font-extrabold tracking-tighter text-white">
                    Ready to curate your digital legacy?
                </h2>
                <p class="text-slate-300 text-xl font-['Manrope'] max-w-xl mx-auto">
                    Join a global community of creators who prioritize quality and depth over the ephemeral feed.
                </p>
            </div>

            {{-- CTA Button --}}
            <div class="relative z-10">
                <button class="bg-white text-[#272c51] px-12 py-5 rounded-full font-extrabold text-xl hover:bg-slate-100 transition-all hover:scale-105 active:scale-95 editorial-shadow">
                    Get Started Free
                </button>
            </div>

        </div>
    </section>

@endsection