<!DOCTYPE html>
<html class="light scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'The Curator')</title>

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
                        "outline": "#6f749e",
                        "secondary-fixed-dim": "#b9bfff",
                        "on-primary-fixed": "#000000",
                        "on-primary-container": "#001f57",
                        "surface-container-highest": "#d8daff",
                        "on-primary-fixed-variant": "#00276b",
                        "on-tertiary-fixed": "#3b003e",
                        "background": "#f8f5ff",
                        "surface-tint": "#0253cd",
                        "primary-container": "#789dff",
                        "on-primary": "#f1f2ff",
                        "surface-container-high": "#dfe0ff",
                        "secondary-dim": "#3744ab",
                        "surface-container-low": "#f1efff",
                        "surface-container-lowest": "#ffffff",
                        "surface": "#f8f5ff",
                        "primary-fixed": "#789dff",
                        "surface-variant": "#d8daff",
                        "tertiary": "#8c3a8c",
                        "on-secondary-fixed": "#17258f",
                        "tertiary-fixed-dim": "#e488df",
                        "on-secondary-fixed-variant": "#3945ac",
                        "surface-container": "#e6e6ff",
                        "on-error": "#ffefee",
                        "error": "#b31b25",
                        "primary": "#0253cd",
                        "primary-dim": "#0048b5",
                        "on-surface-variant": "#545981",
                        "secondary": "#4451b7",
                        "on-secondary": "#f3f1ff",
                        "surface-bright": "#f8f5ff",
                        "inverse-on-surface": "#969ac6",
                        "secondary-container": "#caceff",
                        "tertiary-fixed": "#f395ee",
                        "tertiary-container": "#f395ee",
                        "primary-fixed-dim": "#618eff",
                        "tertiary-dim": "#7f2d7f",
                        "inverse-primary": "#5c8bff",
                        "on-surface": "#272c51",
                        "on-error-container": "#570008",
                        "outline-variant": "#a6aad7",
                        "secondary-fixed": "#caceff",
                        "on-tertiary": "#ffeef9",
                        "on-tertiary-container": "#5e0b61",
                        "on-secondary-container": "#2e3ba2",
                        "error-dim": "#9f0519",
                        "error-container": "#fb5151",
                        "on-background": "#272c51",
                        "inverse-surface": "#05092f",
                        "surface-dim": "#ced1ff",
                        "on-tertiary-fixed-variant": "#69186b"
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
        .primary-gradient {
            background: linear-gradient(135deg, #0253cd 0%, #789dff 100%);
        }
        body {
            font-family: 'Manrope', sans-serif;
            background-color: #f8f5ff;
            min-height: 100dvh;
        }
    </style>

    @stack('styles')
</head>
<body class="text-on-background flex flex-col min-h-screen">

   

    {{-- Page Content --}}
    <main class="flex-grow flex items-center justify-center px-8 py-12 pt-28">
        @yield('content')
    </main>


    @stack('scripts')
</body>
</html>