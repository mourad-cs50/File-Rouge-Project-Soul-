@extends('layouts.auth')

@section('title', 'The Curator | Create Account')

@section('content')

    <div class="w-full max-w-6xl grid grid-cols-2 bg-surface-container-lowest rounded-3xl overflow-hidden shadow-2xl shadow-blue-900/5 min-h-[680px]">

        {{-- ===================== LEFT PANEL: Visual / Brand ===================== --}}
        <div class="relative overflow-hidden group">
            <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuButMzlGIlefgALEMwx2qHQrAfsm8dQE1dWMzXmkWueo2ujaTyzoO6IlkctkSjrbeVdY45aLu4QnachQpw6guNf2fubFYSHyJM5n7OyXCHapjAAJRyOL4gjuFosPz4y4mPAUS3_xa-XEqAyEj5zWwCZHPUatPjRKTHFApyP9wh9TIyh3n4uhuoDznoh8i4_bm4NwTuCKvmf0OAHHlXuArXJCB-cfqd1kJpBtxzTmS5q8M7eRnVJb3xxb6X-bXnuTHS6Amx_xajW_bCl"
                alt="Abstract fluid blue art"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
            />

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-[#0253cd]/25 backdrop-blur-[2px] flex flex-col justify-end p-14 text-white">
                <p class="text-[11px] uppercase tracking-[0.25em] font-bold mb-5 opacity-75">
                    The Digital Atelier
                </p>
                <h2 class="text-4xl xl:text-5xl font-['Plus_Jakarta_Sans'] font-extrabold tracking-tight leading-[1.1] mb-8">
                    Curate your vision,<br>refine your world.
                </h2>

                {{-- Social proof avatars --}}
                <div class="flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <div class="w-9 h-9 rounded-full border-2 border-white bg-slate-200 overflow-hidden">
                            <img
                                class="w-full h-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6ywPX1Xyg3n-PvgAUdC72ECNDUx9bOc13MhMXWITzq2GZClsBZbJNN8Y078SyxUMW-OtZse1erkonAZjQ1RJXdgpyWgVRR3qqj1TLMnbAnDtnmP1TuFYxrbDvRHUob2jZFroo_Xzu1AXVCHGDE-dzTerikK-GgdqvFz-eebs9mhfACZnhMiEDKMRubDe2EsMaaZBiccv-aLLeyoPNCTR8HgErB2RcOjLxG--xeqEYzjFLW6H2AZT_3i4WTPSSfeaefp0EcHNg8BIs"
                                alt="Creator avatar"
                            />
                        </div>
                        <div class="w-9 h-9 rounded-full border-2 border-white bg-slate-200 overflow-hidden">
                            <img
                                class="w-full h-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDLXWlid-ByJ0WzjRK-6AU3N-MoI59LGtRyRIlspjPgzoAXjaTTkwCnHAk2YrUAWxHWgJXP1VWg2rgyC6fKjUJb-4qKl5lijIUwTe3yVJB5G22CS4qHNSRD0EqZABFPj7uMVHP7dMvPRY-MQDpnxgovBELzAfmxykkqI9KnWvzf7-aLRv63CdUEk9yR8rUjg9NHAwCVVk1BU0KkUzl4CFaMfsnTzE0h8BdPLxAdc59qI5WO1kGiuQvPsDm_2KgRkR4WoXBWpHdBGWoR"
                                alt="Creator avatar"
                            />
                        </div>
                        <div class="w-9 h-9 rounded-full border-2 border-white bg-slate-200 overflow-hidden">
                            <img
                                class="w-full h-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDQY1_UKxi3AWiPm4q5p5VEXw7Y-X_1Z-q5kDA6YV0oD9E0BMy-eJkn57Vev5X7g1sUjmeSg1a2gyt8I7hSzKUDpHoQNWv_zJrgEGyvuCB_Q4MYH4Fvf27wUQ9x-UCIOpOY2nFk0PXk-Rw8XgqYbGsbJF79BLAjHnZcOJ8OtbFrqkEzGFVL4y95qk8zN7Df2MSqEXEwZPGT0v62HY_bQCHP5vy_sajhzmJ1TDvkk4p1XYPxNFc3TfJYuRXJ5owifKig_ZQxuaOxg0o2"
                                alt="Creator avatar"
                            />
                        </div>
                    </div>
                    <span class="text-sm font-semibold">Joined by 2k+ curators</span>
                </div>
            </div>
        </div>

        {{-- ===================== RIGHT PANEL: Form ===================== --}}
        <div class="px-16 py-14 flex flex-col justify-center">

            {{-- Heading --}}
            <div class="mb-10">
                <h1 class="text-3xl xl:text-4xl font-['Plus_Jakarta_Sans'] font-bold text-on-surface tracking-tight mb-2">
                    Create Account
                </h1>
                <p class="text-on-surface-variant text-sm font-medium">
                    Start your journey into the fluid gallery today.
                </p>
            </div>

            <div class="space-y-6">


                {{-- Divider --}}
                <div class="relative flex items-center py-1">
                    <div class="flex-grow border-t border-outline-variant/30"></div>
                    <span class="flex-shrink mx-4 text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">
                        use email
                    </span>
                    <div class="flex-grow border-t border-outline-variant/30"></div>
                </div>

                {{-- Registration Form --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    {{-- Full Name --}}
                    <div class="space-y-2">
                        <label for="name" class="block text-[10px] uppercase tracking-[0.12em] font-bold text-on-surface-variant ml-1">
                            Full Name
                        </label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            autocomplete="name"
                            required
                            placeholder="John Doe"
                            value="{{ old('name') }}"
                            class="w-full px-5 py-4 rounded-xl bg-surface-container-low border-transparent focus:border-primary/20 focus:ring-0 focus:bg-surface-container-lowest text-on-surface placeholder:text-outline/50 transition-all font-medium text-sm"
                        />
                        @error('name')
                            <p class="text-xs text-error ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="space-y-2">
                        <label for="email" class="block text-[10px] uppercase tracking-[0.12em] font-bold text-on-surface-variant ml-1">
                            Email Address
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            placeholder="hello@curator.com"
                            value="{{ old('email') }}"
                            class="w-full px-5 py-4 rounded-xl bg-surface-container-low border-transparent focus:border-primary/20 focus:ring-0 focus:bg-surface-container-lowest text-on-surface placeholder:text-outline/50 transition-all font-medium text-sm"
                        />
                        @error('email')
                            <p class="text-xs text-error ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="space-y-2">
                        <label for="password" class="block text-[10px] uppercase tracking-[0.12em] font-bold text-on-surface-variant ml-1">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                required
                                placeholder="••••••••"
                                class="w-full px-5 py-4 rounded-xl bg-surface-container-low border-transparent focus:border-primary/20 focus:ring-0 focus:bg-surface-container-lowest text-on-surface placeholder:text-outline/50 transition-all font-medium text-sm"
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

                    {{-- Submit --}}
                    <div class="pt-3">
                        <button
                            type="submit"
                            class="w-full py-4 rounded-full primary-gradient text-white font-bold tracking-tight text-sm shadow-xl shadow-primary/20 hover:opacity-90 hover:scale-[0.99] transition-all duration-200"
                        >
                           <a href="{{ route('dashboard.sections') }}" class="text-white no-underline">
                                Create Account
                            </a>
                        </button>
                    </div>
                </form>

                {{-- Login redirect --}}
                <div class="text-center pt-2">
                    <p class="text-sm text-on-surface-variant font-medium">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-primary font-bold hover:underline underline-offset-4 ml-1">
                            Log in
                        </a>
                    </p>
                </div>

            </div>

            {{-- Trust badges --}}
            <div class="mt-10 flex justify-center gap-8 opacity-40">
                <span class="text-[10px] uppercase tracking-widest font-bold text-on-surface">Secure SSL</span>
                <span class="text-[10px] uppercase tracking-widest font-bold text-on-surface">Encrypted Data</span>
                <span class="text-[10px] uppercase tracking-widest font-bold text-on-surface">Privacy First</span>
            </div>

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