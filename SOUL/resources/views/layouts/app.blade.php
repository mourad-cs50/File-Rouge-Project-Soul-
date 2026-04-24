<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'The Curator | Share Your World')</title>

    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Manrope:wght@400;500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "secondary-fixed": "#caceff",
                        "on-tertiary-container": "#5e0b61",
                        "surface-bright": "#f8f5ff",
                        "surface-variant": "#d8daff",
                        "primary-dim": "#0048b5",
                        "secondary-container": "#caceff",
                        "on-primary": "#f1f2ff",
                        "surface-container-high": "#dfe0ff",
                        "background": "#f8f5ff",
                        "tertiary-fixed-dim": "#e488df",
                        "primary-fixed-dim": "#618eff",
                        "on-primary-fixed-variant": "#00276b",
                        "outline": "#6f749e",
                        "secondary-dim": "#3744ab",
                        "on-tertiary-fixed-variant": "#69186b",
                        "primary": "#0253cd",
                        "on-error": "#ffefee",
                        "on-secondary-fixed": "#17258f",
                        "on-background": "#272c51",
                        "on-primary-fixed": "#000000",
                        "inverse-surface": "#05092f",
                        "tertiary-fixed": "#f395ee",
                        "surface-dim": "#ced1ff",
                        "error-dim": "#9f0519",
                        "on-tertiary": "#ffeef9",
                        "tertiary-container": "#f395ee",
                        "secondary": "#4451b7",
                        "on-primary-container": "#001f57",
                        "tertiary-dim": "#7f2d7f",
                        "secondary-fixed-dim": "#b9bfff",
                        "on-surface": "#272c51",
                        "surface-container": "#e6e6ff",
                        "on-surface-variant": "#545981",
                        "on-error-container": "#570008",
                        "tertiary": "#8c3a8c",
                        "on-secondary-container": "#2e3ba2",
                        "error-container": "#fb5151",
                        "surface": "#f8f5ff",
                        "surface-container-highest": "#d8daff",
                        "primary-fixed": "#789dff",
                        "surface-tint": "#0253cd",
                        "error": "#b31b25",
                        "on-secondary-fixed-variant": "#3945ac",
                        "inverse-primary": "#5c8bff",
                        "surface-container-low": "#f1efff",
                        "outline-variant": "#a6aad7",
                        "on-tertiary-fixed": "#3b003e",
                        "on-secondary": "#f3f1ff",
                        "primary-container": "#789dff",
                        "surface-container-lowest": "#ffffff",
                        "inverse-on-surface": "#969ac6"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    fontFamily: {
                        "headline": ["Plus Jakarta Sans"],
                        "body": ["Manrope"],
                        "label": ["Manrope"]
                    }
                }
            }
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Manrope', sans-serif;
            background-color: #f8f5ff;
            color: #272c51;
        }
        .editorial-shadow {
            box-shadow: 0 20px 40px rgba(39, 44, 81, 0.06);
        }
        .hero-gradient {
            background: linear-gradient(135deg, #0253cd 0%, #789dff 100%);
        }
    </style>

    @stack('styles')
</head>
<body class="flex flex-col min-h-screen">

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    @stack('scripts')
</body>
</html>