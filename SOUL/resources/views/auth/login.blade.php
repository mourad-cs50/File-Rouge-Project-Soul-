@extends('layouts.auth')

@section('title', 'Login - The Curator')

@section('content')

    {{-- Background decorative blurs --}}
    <div class="absolute top-[-10%] right-[-5%] w-[40rem] h-[40rem] bg-primary-container/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] left-[-5%] w-[30rem] h-[30rem] bg-secondary-container/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-6xl grid grid-cols-2 bg-surface-container-lowest rounded-2xl overflow-hidden shadow-[0_20px_40px_rgba(39,44,81,0.06)] relative z-10 min-h-[640px]">

        {{-- ===================== LEFT PANEL: Visual Narrative ===================== --}}
        <div class="relative group overflow-hidden">
            <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCOZHf9p-EXqx6wSZYvj6LMIbu4wq89il5X-hDOBU5_M_-s22f6CWj88uY2HbxxGTwXpvx1OGKIhrs2SYyBDiTuuKBQdsXxqsxHWqbr5pSZi4fOSLOC4WkgmLW8E4bBqgJJPQNwTzZi-BCIsYnjSNdg_bzwp_t3sd33C9AISYji5AUN02kbjgIK8rmNlK49B6xDaILV0DgOGqgiclCiTDj19GUWWnmnY42hnMWBizcYInG8gBKHEroq_VZh008PUzbDg1hgSaeLhC2o"
                alt="Minimalist art gallery interior"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent flex flex-col justify-end p-14">
                <span class="text-[10px] uppercase tracking-[0.2em] font-['Manrope'] font-bold text-white/80 mb-3">
                    Curated Experience
                </span>
                <h2 class="text-3xl xl:text-4xl font-['Plus_Jakarta_Sans'] font-bold text-white leading-tight mb-5">
                    Step back into the world of digital curation.
                </h2>
                <p class="text-white/70 text-sm leading-relaxed max-w-sm">
                    Join our community of over 50,000 creators and collectors in a space designed for fluid interaction and high-fidelity discovery.
                </p>
            </div>
        </div>

        {{-- ===================== RIGHT PANEL: Login Form ===================== --}}
        <div class="px-16 py-14 flex flex-col justify-center">

            {{-- Heading --}}
            <div class="mb-10">
                <h1 class="text-3xl xl:text-4xl font-['Plus_Jakarta_Sans'] font-extrabold text-on-surface tracking-tight mb-2">
                    Welcome Back
                </h1>
                <p class="text-on-surface-variant text-sm font-medium">
                    Please enter your details to access your collection.
                </p>
            </div>

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                {{-- Email --}}
                <div class="space-y-2">
                    <label for="email" class="block text-[11px] uppercase tracking-wider font-['Manrope'] font-bold text-on-surface-variant ml-1">
                        Email Address
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        placeholder="curator@example.com"
                        value="{{ old('email') }}"
                        class="w-full px-5 py-4 bg-surface-container-highest border-none rounded-xl text-on-surface placeholder:text-outline-variant focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all font-medium text-sm"
                    />
                    @error('email')
                        <p class="text-xs text-error ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label for="password" class="text-[11px] uppercase tracking-wider font-['Manrope'] font-bold text-on-surface-variant">
                            Password
                        </label>
                    </div>
                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            placeholder="••••••••"
                            class="w-full px-5 py-4 bg-surface-container-highest border-none rounded-xl text-on-surface placeholder:text-outline-variant focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all font-medium text-sm"
                        />
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-outline/60 hover:text-primary transition-colors"
                        >
                            <span class="material-symbols-outlined text-lg" id="toggle-icon">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-error ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-3 px-1">
                    <input
                        id="remember"
                        name="remember"
                        type="checkbox"
                        class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20"
                    />
                    <label for="remember" class="text-sm text-on-surface-variant font-medium cursor-pointer">
                        Remember me for 30 days
                    </label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full py-4 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold rounded-full shadow-lg hover:shadow-primary/20 hover:scale-[0.99] transition-all active:scale-95"
                >
                    Sign In
                </button>

            </form>

            {{-- Divider --}}
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-surface-container"></div>
                </div>
                <div class="relative flex justify-center text-[10px] uppercase tracking-widest font-['Manrope']">
                    <span class="bg-surface-container-lowest px-4 text-outline-variant font-bold">Or continue with</span>
                </div>
            </div>

            {{-- Social Logins --}}
            <div class="grid grid-cols-2 gap-4">
                <button type="button" class="flex items-center justify-center gap-3 py-3.5 border border-outline-variant/20 rounded-xl hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-xl text-on-surface">cloud</span>
                    <span class="text-xs font-bold font-['Manrope']">Google</span>
                </button>
                <button type="button" class="flex items-center justify-center gap-3 py-3.5 border border-outline-variant/20 rounded-xl hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-xl text-on-surface">terminal</span>
                    <span class="text-xs font-bold font-['Manrope']">Github</span>
                </button>
            </div>

            {{-- Sign up redirect --}}
            <p class="mt-10 text-center text-sm text-on-surface-variant font-medium">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-primary font-bold hover:underline underline-offset-4 ml-1">
                    Sign up
                </a>
            </p>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('toggle-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }
</script>
@endpush