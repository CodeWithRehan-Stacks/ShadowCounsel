<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShadowCounsel — AI-powered financial legal intelligence. Multi-agent analysis of SEC regulations, corporate finance compliance, and complex tax law.">
    <title>ShadowCounsel — AI Financial Legal Intelligence</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.7s ease-out forwards',
                        'fade-in': 'fadeIn 1s ease-out forwards',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        fadeInUp: { '0%': { opacity: '0', transform: 'translateY(24px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        float: { '0%,100%': { transform: 'translateY(0px)' }, '50%': { transform: 'translateY(-12px)' } },
                        glow: { '0%': { boxShadow: '0 0 20px rgba(124,58,237,0.3)' }, '100%': { boxShadow: '0 0 40px rgba(124,58,237,0.7)' } },
                    },
                }
            }
        }
    </script>

    <style>
        body { background-color: #080b12; font-family: 'Inter', sans-serif; }

        /* Animated gradient orbs */
        .orb { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; }

        /* Grid pattern */
        .bg-grid {
            background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 40%, #4f46e5 70%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glassmorphism card */
        .glass {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.07);
        }

        /* Stagger animations */
        .stagger-1 { animation: fadeInUp 0.7s ease-out 0.1s both; }
        .stagger-2 { animation: fadeInUp 0.7s ease-out 0.2s both; }
        .stagger-3 { animation: fadeInUp 0.7s ease-out 0.35s both; }
        .stagger-4 { animation: fadeInUp 0.7s ease-out 0.5s both; }
        .stagger-5 { animation: fadeInUp 0.7s ease-out 0.65s both; }

        /* Noise overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>

<body class="text-gray-200 min-h-screen relative overflow-x-hidden selection:bg-violet-500/30">

    <!-- Background Layer -->
    <div class="fixed inset-0 bg-grid -z-10"></div>
    <div class="orb w-[700px] h-[700px] bg-violet-600/8 top-[-200px] left-[-200px] -z-10"></div>
    <div class="orb w-[500px] h-[500px] bg-indigo-600/8 bottom-[-100px] right-[-100px] -z-10"></div>
    <div class="orb w-[300px] h-[300px] bg-purple-500/6 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10"></div>

    <!-- ─────────────── NAVBAR ─────────────── -->
    <header class="relative z-10 w-full">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <img src="{{ asset('svg/Logo.svg') }}" alt="ShadowCounsel" class="h-9 w-auto">
            </a>


            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="text-sm text-gray-400 hover:text-white px-4 py-2 rounded-lg transition-colors font-medium">
                    Log in
                </a>
                <a href="{{ route('register') }}"
                   class="text-sm font-semibold text-white bg-violet-600 hover:bg-violet-500 px-4 py-2 rounded-xl transition-all shadow-sm shadow-violet-600/30 hover:shadow-md hover:shadow-violet-600/30">
                    Get Started
                </a>
            </div>
        </div>
    </header>

    <!-- ─────────────── HERO ─────────────── -->
    <main class="relative z-10">
        <section class="max-w-5xl mx-auto px-6 pt-20 pb-32 text-center flex flex-col items-center">

            <!-- Badge -->
            <div class="stagger-1 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-xs text-violet-300 font-medium tracking-wider uppercase mb-8">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-pulse-slow"></span>
                Under Development — Coming Soon
            </div>

            <!-- Headline -->
            <h1 class="stagger-2 text-4xl sm:text-5xl md:text-7xl font-extrabold text-white tracking-tight leading-[1.08] mb-6">
                Intelligent Advisory<br>
                <span class="gradient-text">Redefined by AI</span>
            </h1>

            <!-- Sub-headline -->
            <p class="stagger-3 text-gray-400 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed mb-10">
                A multi-agent AI architecture powered by Nemotron 3 Ultra. Designed to analyze corporate finance compliance,  SEC  regulations, and complex  tax law instantly and accurately.
            </p>

            <!-- Notify Form -->
            <div class="stagger-4 w-full max-w-md mx-auto mb-6">
                <form action="{{ route('subscribe.store') }}" method="POST">
                    @csrf
                    <div class="flex items-center gap-2 p-1.5 rounded-2xl glass focus-within:border-violet-500/40 transition-all shadow-2xl">
                        <input
                            type="email"
                            name="email"
                            placeholder="Enter your email for early access"
                            class="flex-1 bg-transparent px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-0 min-w-0"
                            required>
                        <button
                            type="submit"
                            class="bg-violet-600 hover:bg-violet-500 active:scale-95 text-white font-semibold text-sm px-5 py-2.5 rounded-xl whitespace-nowrap transition-all duration-200 shadow-lg shadow-violet-600/25">
                            Notify Me →
                        </button>
                    </div>
                </form>
                <p class="text-xs text-gray-600 mt-3">No spam. We'll only notify you when we launch.</p>
            </div>

            <!-- Social Proof -->
            <div class="stagger-5 flex items-center gap-2 text-xs text-gray-600">
                <div class="flex -space-x-1.5">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-violet-400 to-purple-600 border border-gray-900 flex items-center justify-center text-[9px] text-white font-bold">A</div>
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 border border-gray-900 flex items-center justify-center text-[9px] text-white font-bold">B</div>
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 border border-gray-900 flex items-center justify-center text-[9px] text-white font-bold">C</div>
                </div>
                <span>Join 200+ professionals on the waitlist</span>
            </div>
        </section>

        <!-- ─────────────── FEATURES ─────────────── -->
        <section id="features" class="max-w-6xl mx-auto px-6 pb-28">
            <div class="text-center mb-14">
                <p class="text-xs font-semibold text-violet-400 uppercase tracking-widest mb-3">Capabilities</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Built for Legal Precision</h2>
                <p class="text-gray-400 max-w-xl mx-auto text-sm leading-relaxed">Purpose-built AI agents that work in concert, cross-referencing regulatory frameworks in real time.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <!-- Feature 1 -->
                <div class="glass rounded-2xl p-6 hover:border-violet-500/20 transition-all group cursor-default">
                    <div class="w-11 h-11 rounded-xl bg-violet-500/10 flex items-center justify-center mb-5 group-hover:bg-violet-500/20 transition-colors">
                        <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-white mb-2">SEC Compliance Analysis</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Automated review of SEC filings, disclosures, and regulatory requirements with deep citation-level accuracy.</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass rounded-2xl p-6 hover:border-violet-500/20 transition-all group cursor-default">
                    <div class="w-11 h-11 rounded-xl bg-violet-500/10 flex items-center justify-center mb-5 group-hover:bg-violet-500/20 transition-colors">
                        <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-white mb-2">Tax Law Intelligence</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Parse complex multi-jurisdictional tax codes and identify optimization opportunities with explainable reasoning.</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass rounded-2xl p-6 hover:border-violet-500/20 transition-all group cursor-default">
                    <div class="w-11 h-11 rounded-xl bg-violet-500/10 flex items-center justify-center mb-5 group-hover:bg-violet-500/20 transition-colors">
                        <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"></path></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-white mb-2">Multi-Agent Orchestration</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Multiple specialized AI agents collaborate to cross-reference frameworks, validate findings, and surface conflicts.</p>
                </div>

                <!-- Feature 4 -->
                <div class="glass rounded-2xl p-6 hover:border-violet-500/20 transition-all group cursor-default">
                    <div class="w-11 h-11 rounded-xl bg-violet-500/10 flex items-center justify-center mb-5 group-hover:bg-violet-500/20 transition-colors">
                        <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-white mb-2">Risk Assessment</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Proactive identification of regulatory risks before they escalate, with quantified exposure analysis and mitigation paths.</p>
                </div>

                <!-- Feature 5 -->
                <div class="glass rounded-2xl p-6 hover:border-violet-500/20 transition-all group cursor-default">
                    <div class="w-11 h-11 rounded-xl bg-violet-500/10 flex items-center justify-center mb-5 group-hover:bg-violet-500/20 transition-colors">
                        <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-white mb-2">Conversational Interface</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Ask complex legal questions in plain language and receive precise, well-cited answers in seconds.</p>
                </div>

                <!-- Feature 6 -->
                <div class="glass rounded-2xl p-6 hover:border-violet-500/20 transition-all group cursor-default">
                    <div class="w-11 h-11 rounded-xl bg-violet-500/10 flex items-center justify-center mb-5 group-hover:bg-violet-500/20 transition-colors">
                        <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-white mb-2">Real-time Monitoring</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Continuous surveillance of regulatory updates, enforcement actions, and rulemaking activity across key agencies.</p>
                </div>
            </div>
        </section>

        <!-- ─────────────── ABOUT / CTA ─────────────── -->
        <section id="about" class="max-w-4xl mx-auto px-6 pb-28 text-center">
            <div class="glass rounded-3xl p-10 sm:p-16 relative overflow-hidden">
                <!-- Subtle glow inside card -->
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(124,58,237,0.08)_0%,transparent_70%)]"></div>
                <div class="relative z-10">
                    <p class="text-xs font-semibold text-violet-400 uppercase tracking-widest mb-4">Powered by Nemotron 3 Ultra</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-5 leading-tight">
                        Your AI Counsel is<br>Almost Ready
                    </h2>
                    <p class="text-gray-400 text-sm max-w-lg mx-auto leading-relaxed mb-8">
                        We're putting the finishing touches on a product that will fundamentally change how legal and financial professionals navigate regulatory complexity.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('register') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-violet-600/25 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Start for Free
                        </a>
                        <a href="{{ route('login') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-gray-300 hover:text-white border border-gray-700 hover:border-gray-500 rounded-xl transition-all duration-200">
                            Sign In
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- ─────────────── FOOTER ─────────────── -->
    <footer class="relative z-10 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('svg/Logo.svg') }}" alt="ShadowCounsel" class="h-7 w-auto opacity-60">
            </div>
            <p class="text-xs text-gray-600 text-center">
                &copy; {{ date('Y') }} ShadowCounsel. All rights reserved.
            </p>
            <div class="flex items-center gap-5">
                <a href="{{ route('login') }}" class="text-xs text-gray-600 hover:text-gray-400 transition-colors">Log in</a>
                <a href="{{ route('register') }}" class="text-xs text-gray-600 hover:text-gray-400 transition-colors">Register</a>
            </div>
        </div>
    </footer>

    <!-- ─────────────── SUCCESS TOAST ─────────────── -->
    @if(session('success'))
    <div id="successToast" class="fixed bottom-6 right-6 z-50 transform translate-y-4 opacity-0 transition-all duration-500 ease-out pointer-events-none">
        <div class="bg-[#0f1117] border border-emerald-500/30 rounded-2xl p-4 shadow-2xl flex items-start gap-3 max-w-xs backdrop-blur-xl">
            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white">You're on the list!</p>
                <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="closeToast()" class="text-gray-600 hover:text-gray-300 transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('successToast');
            if (toast) {
                setTimeout(() => {
                    toast.classList.remove('translate-y-4', 'opacity-0', 'pointer-events-none');
                }, 150);
                setTimeout(() => closeToast(), 6000);
            }
        });

        function closeToast() {
            const toast = document.getElementById('successToast');
            if (toast) {
                toast.classList.add('translate-y-4', 'opacity-0', 'pointer-events-none');
            }
        }
    </script>
    @endif

</body>

</html>