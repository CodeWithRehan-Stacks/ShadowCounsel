<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') — {{ config('app.name', 'ShadowCounsel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 antialiased min-h-screen selection:bg-violet-500/30">

    <div class="flex min-h-screen w-full">
        <!-- LEFT BRAND PANE (Desktop Only 50%) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#0f1117] overflow-hidden items-center justify-center border-r border-gray-200 dark:border-gray-800/60">
            
            <!-- Abstract background elements -->
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-violet-900/20 via-[#0f1117] to-[#0f1117]"></div>
            <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-96 h-96 bg-violet-600/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-3xl"></div>

            <!-- Content -->
            <div class="relative z-10 w-full max-w-lg px-12 text-center flex flex-col items-center">
                <a href="{{ url('/') }}" class="inline-block mb-8 transition-transform hover:scale-[1.02]">
                    <img src="{{ asset('svg/Logo.svg') }}" alt="ShadowCounsel" class="h-14 w-auto ">
                </a>
                
                <h2 class="text-3xl font-bold text-white mb-4 tracking-tight">Intelligent Advisory, Redefined.</h2>
                <p class="text-gray-400 text-lg leading-relaxed">
                    Experience the future of seamless, AI-powered counsel. Secure, precise, and entirely tailored to your workflow.
                </p>
            </div>
        </div>

        <!-- RIGHT FORM PANE (100% on Mobile, 50% on Desktop) -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-12 relative bg-white dark:bg-[#1a1d24]">
            
            <!-- Mobile Logo (visible only on small screens) -->
            <div class="lg:hidden absolute top-8 left-1/2 -translate-x-1/2">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('svg/Logo.svg') }}" alt="ShadowCounsel" class="h-10 w-auto dark:brightness-0 dark:invert">
                </a>
            </div>

            <!-- Inject the authentication form here -->
            <div class="w-full max-w-sm mt-12 lg:mt-0">
                @yield('content')
            </div>

        </div>
    </div>

</body>
</html>
