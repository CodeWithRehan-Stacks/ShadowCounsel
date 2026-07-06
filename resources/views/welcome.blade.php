<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShadowCounsel - Coming Soon</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Elms+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0b0f19;
        }

        .glow-text {
            text-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
            font-family: "Elms Sans", sans-serif;
        }
    </style>
</head>

<body class="text-gray-200 font-sans min-h-screen flex flex-col justify-between items-center p-6 relative overflow-x-hidden">

    <!-- Ambient Glow Background -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-500/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>

    <!-- Top Navigation / Logo -->
    <div class="w-full max-w-6xl flex justify-between items-center py-4">
        <img src="{{ asset('svg/Logo.svg') }}" alt="ShadowCounsel" class="h-10 w-auto">
        <div class="text-xs tracking-widest text-gray-500 uppercase hidden sm:flex items-center gap-2">
            <a href="{{ route('login') }}" class="px-3 py-2 hover:text-gray-300 transition-colors rounded-lg">Log in</a>
            <a href="{{ route('register') }}" class="px-3 py-2 hover:text-gray-300 transition-colors rounded-lg">Register</a>
        </div>
    </div>

    <!-- Main Content Box -->
    <div class="max-w-2xl text-center my-auto space-y-8 z-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-xs text-blue-400 font-medium tracking-wide uppercase">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            Under Development
        </div>

        <h1 class="text-2xl md:text-5xl font-extrabold text-white tracking-tight leading-tight glow-text">
            The Future of Financial <br>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400">
                Legal Intelligence
            </span>
        </h1>

        <p class="text-gray-400 text-sm md:text-base max-w-xl mx-auto leading-relaxed" style="font-family: 'Elms Sans', sans-serif;">
            An automated multi-agent architecture powered by Nemotron 3 Ultra. Designed to analyze corporate finance compliance, SEC regulations, and
            complex tax laws—launching soon.
        </p>

        <!-- Notify Form -->
        <form action="{{ route('subscribe.store') }}" method="POST" class="max-w-md mx-auto flex items-center bg-gray-900/50 border border-gray-800 rounded-xl p-1.5 focus-within:border-blue-500/50 transition-all shadow-xl backdrop-blur-sm">
            @csrf
            <input
                type="email"
                name="email"
                placeholder="Enter your email to get notified"
                class="w-full bg-transparent px-4 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-0"
                required>
            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-500 text-white font-medium text-sm px-5 py-2.5 rounded-lg whitespace-nowrap transition-all duration-200 active:scale-95 shadow-lg shadow-blue-600/20">
                Notify Me
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="w-full max-w-6xl text-center border-t border-gray-900 pt-6 text-xs text-gray-600">
        &copy; {{ date('Y') }} ShadowCounsel. All rights reserved. Finance-field legal analysis systems.
    </div>

    <!-- Animated Success Popup Modal -->
    @if(session('success'))
    <div id="successModal" class="fixed bottom-6 right-6 z-50 transform translate-y-10 opacity-0 transition-all duration-500 ease-out pointer-events-none">
        <div class="bg-gray-900 border border-emerald-500/30 rounded-xl p-4 shadow-2xl flex items-start gap-3 max-w-sm backdrop-blur-md">
            <div class="p-1.5 bg-emerald-500/10 text-emerald-400 rounded-lg shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-white">Successfully Subscribed!</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="closeModal()" class="text-gray-500 hover:text-gray-300 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <script>
        // Trigger presentation animation shortly after load
        window.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('successModal');
            if (modal) {
                setTimeout(() => {
                    modal.classList.remove('translate-y-10', 'opacity-0', 'pointer-events-none');
                }, 100);

                // Auto-hide popup after 5 seconds
                setTimeout(() => {
                    closeModal();
                }, 5100);
            }
        });

        function closeModal() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.classList.add('translate-y-10', 'opacity-0', 'pointer-events-none');
            }
        }
    </script>
    @endif

</body>

</html>