<!DOCTYPE html>
<html class="light scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Dashboard - The Curator')</title>

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
                        "secondary-container": "#caceff",
                        "tertiary-dim": "#7f2d7f",
                        "outline-variant": "#a6aad7",
                        "error-container": "#fb5151",
                        "surface": "#f8f5ff",
                        "outline": "#6f749e",
                        "surface-container-highest": "#d8daff",
                        "error": "#b31b25",
                        "background": "#f8f5ff",
                        "surface-dim": "#ced1ff",
                        "tertiary": "#8c3a8c",
                        "on-secondary": "#f3f1ff",
                        "on-secondary-fixed": "#17258f",
                        "on-background": "#272c51",
                        "surface-container-high": "#dfe0ff",
                        "surface-tint": "#0253cd",
                        "surface-variant": "#d8daff",
                        "on-tertiary-container": "#5e0b61",
                        "on-primary-fixed-variant": "#00276b",
                        "primary-fixed-dim": "#618eff",
                        "surface-container-lowest": "#ffffff",
                        "primary-fixed": "#789dff",
                        "primary-container": "#789dff",
                        "on-error-container": "#570008",
                        "error-dim": "#9f0519",
                        "on-primary": "#f1f2ff",
                        "surface-bright": "#f8f5ff",
                        "tertiary-fixed": "#f395ee",
                        "inverse-primary": "#5c8bff",
                        "on-secondary-container": "#2e3ba2",
                        "on-surface-variant": "#545981",
                        "on-tertiary": "#ffeef9",
                        "surface-container-low": "#f1efff",
                        "surface-container": "#e6e6ff",
                        "tertiary-fixed-dim": "#e488df",
                        "on-secondary-fixed-variant": "#3945ac",
                        "secondary-fixed-dim": "#b9bfff",
                        "on-primary-fixed": "#000000",
                        "tertiary-container": "#f395ee",
                        "inverse-on-surface": "#969ac6",
                        "on-primary-container": "#001f57",
                        "secondary-fixed": "#caceff",
                        "primary-dim": "#0048b5",
                        "secondary": "#4451b7",
                        "primary": "#0253cd",
                        "on-error": "#ffefee",
                        "on-surface": "#272c51",
                        "secondary-dim": "#3744ab",
                        "on-tertiary-fixed-variant": "#69186b",
                        "on-tertiary-fixed": "#3b003e",
                        "inverse-surface": "#05092f"
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
        }
        .editorial-shadow {
            box-shadow: 0 20px 40px rgba(39, 44, 81, 0.06);
        }
        .nav-link-active {
            background-color: #dfe0ff;
            color: #0253cd;
            font-weight: 700;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-surface text-on-surface min-h-screen">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('components.manager.sidebar')

        {{-- Main area: topbar + content --}}
        <div class="flex flex-col flex-1 min-w-0">

            {{-- Top bar --}}
            @include('components.manager.topbar')

            {{-- Page content --}}
            <main class="flex-1 px-10 py-10 overflow-y-auto">
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>