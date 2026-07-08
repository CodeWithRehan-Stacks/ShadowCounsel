@extends('layouts.chat')

@section('title', 'ShadowFinance AI')
@section('page-title', 'ShadowFinance AI')

@section('content')

<style>
    /* ── Pulse ring ── */
    @keyframes ring-pulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(124, 58, 237, .55);
        }

        50% {
            box-shadow: 0 0 0 7px rgba(124, 58, 237, 0);
        }
    }

    .ring-pulse {
        animation: ring-pulse 2s ease-in-out infinite;
    }

    /* ── Entrance ── */
    @keyframes fade-up {
        from {
            opacity: 0;
            transform: translateY(14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-up {
        animation: fade-up .45s cubic-bezier(.22, 1, .36, 1) both;
    }

    /* ── Shimmer skeleton ── */
    @keyframes shimmer {
        0% {
            background-position: -600px 0;
        }

        100% {
            background-position: 600px 0;
        }
    }

    .skeleton {
        background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
        background-size: 600px 100%;
        animation: shimmer 1.4s infinite;
        border-radius: .5rem;
    }

    .dark .skeleton {
        background: linear-gradient(90deg, #1f2937 25%, #374151 50%, #1f2937 75%);
        background-size: 600px 100%;
    }

    /* ── Glow border on focus ── */
    .glow-focus:focus-within {
        box-shadow: 0 0 0 3px rgba(139, 92, 246, .18), 0 0 20px rgba(139, 92, 246, .08);
    }

    /* ── Chat prose ── */
    .fin-prose {
        font-size: .9rem;
        line-height: 1.7;
        word-break: break-word;
    }

    .fin-prose p {
        margin-bottom: .8rem;
    }

    .fin-prose p:last-child {
        margin-bottom: 0;
    }

    .fin-prose ul {
        list-style-type: disc;
        padding-left: 1.4rem;
        margin-bottom: .8rem;
    }

    .fin-prose ol {
        list-style-type: decimal;
        padding-left: 1.4rem;
        margin-bottom: .8rem;
    }

    .fin-prose li {
        margin-bottom: .25rem;
    }

    .fin-prose h1,
    .fin-prose h2,
    .fin-prose h3 {
        font-weight: 700;
        margin: .9rem 0 .4rem;
    }

    .fin-prose h2 {
        font-size: 1.1rem;
    }

    .fin-prose h3 {
        font-size: 1rem;
    }

    .fin-prose pre {
        background: #0f172a;
        border-radius: .65rem;
        padding: .85rem 1rem;
        overflow-x: auto;
        margin-bottom: .8rem;
    }

    .fin-prose code {
        font-family: ui-monospace, monospace;
        font-size: .82em;
        background: rgba(139, 92, 246, .14);
        padding: .15em .4em;
        border-radius: .25rem;
        color: #a78bfa;
    }

    .fin-prose pre code {
        background: transparent;
        padding: 0;
        color: #e2e8f0;
    }

    .fin-prose table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: .8rem;
        font-size: .85rem;
    }

    .fin-prose th,
    .fin-prose td {
        border: 1px solid #e5e7eb;
        padding: .55rem .75rem;
    }

    .dark .fin-prose th,
    .dark .fin-prose td {
        border-color: #374151;
    }

    .fin-prose th {
        background: #f9fafb;
        font-weight: 600;
    }

    .dark .fin-prose th {
        background: #1f2937;
    }

    .fin-prose a {
        color: #7c3aed;
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    .dark .fin-prose a {
        color: #a78bfa;
    }

    .fin-prose blockquote {
        border-left: 3px solid #7c3aed;
        padding-left: 1rem;
        color: #6b7280;
        font-style: italic;
        margin-bottom: .8rem;
    }

    /* ── Scrollbar ── */
    .fin-scroll::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }

    .fin-scroll::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 99px;
    }

    .dark .fin-scroll::-webkit-scrollbar-thumb {
        background: #374151;
    }
</style>

{{-- ══ ROOT ALPINE COMPONENT ══ --}}
<div class="flex flex-col h-full bg-gray-50 dark:bg-gray-950 overflow-hidden" x-data="financeApp()" x-init="init()">

    {{-- ── MAIN CONTENT CONTAINER (VERTICAL FLEX) ── --}}
    <div class="flex flex-col flex-1 min-h-0 max-w-5xl mx-auto w-full overflow-hidden">

        {{-- ── MESSAGES AREA ── --}}
        <div id="finMessages" class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6 fin-scroll" style="scroll-behavior:smooth">

            {{-- Welcome state --}}
            <div x-show="messages.length === 0" x-cloak
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="flex flex-col items-center justify-center min-h-[80%] text-center px-4 py-10 fade-up">

                {{-- Glowing AI badge --}}
                <div class="relative mb-7">
                    <div class="absolute inset-0 rounded-full bg-violet-500/25 blur-2xl scale-150 animate-pulse"></div>
                    <div class="relative w-24 h-24 rounded-full bg-gradient-to-br from-violet-100 to-purple-100 dark:from-violet-900/40 dark:to-purple-900/40 border-2 border-violet-200 dark:border-violet-700/60 shadow-xl flex items-center justify-center">
                        <svg class="w-12 h-12 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"></path>
                        </svg>
                        {{-- Live indicator --}}
                        <span class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-900 rounded-full ring-pulse"></span>
                    </div>
                </div>

                {{-- Personalized greeting --}}
                <p class="text-sm font-semibold text-violet-500 dark:text-violet-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/></svg>
                    <span x-text="greeting"></span>, {{ auth()->user()->name }}
                </p>

                {{-- Main heading --}}
                <h2 class="text-3xl font-extrabold bg-gradient-to-r from-violet-600 via-purple-600 to-violet-500 dark:from-violet-400 dark:via-purple-400 dark:to-violet-300 bg-clip-text text-transparent mb-3 tracking-tight">
                    How can I help you today?
                </h2>
                <p class="text-[15px] text-gray-500 dark:text-gray-400 max-w-lg mb-6 leading-relaxed">
                    I'm your dedicated AI finance expert. Ask me about investments, cash flow, financial ratios, tax strategies, business valuation, and more.
                </p>

               

                {{-- Section label --}}
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-8 h-px bg-gray-200 dark:bg-gray-700"></span>
                    Try asking
                    <span class="w-8 h-px bg-gray-200 dark:bg-gray-700"></span>
                </p>

                {{-- Starter grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full max-w-2xl">
                    <button @click="fillInput('What is the Price-to-Earnings ratio and how do I use it to evaluate stocks?')"
                        aria-label="Ask about P/E ratio"
                        class="group text-left p-5 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-800/40 backdrop-blur-sm hover:border-violet-300 dark:hover:border-violet-600 hover:shadow-lg hover:shadow-violet-100 dark:hover:shadow-violet-900/20 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-violet-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:enter-delay="100">
                        <div class="w-10 h-10 rounded-full bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center mb-3 text-violet-600 dark:text-violet-400 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <span class="block text-sm font-bold text-gray-900 dark:text-white mb-1 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Explain P/E ratio</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400 line-clamp-2">Learn how to evaluate stock valuations.</span>
                    </button>

                    <button @click="fillInput('What are the best strategies to manage cash flow for a small business?')"
                        aria-label="Ask about cash flow management"
                        class="group text-left p-5 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-800/40 backdrop-blur-sm hover:border-emerald-300 dark:hover:border-emerald-600 hover:shadow-lg hover:shadow-emerald-100 dark:hover:shadow-emerald-900/20 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:enter-delay="200">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-3 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="block text-sm font-bold text-gray-900 dark:text-white mb-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Cash flow tips</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400 line-clamp-2">Strategies to manage business cash flow.</span>
                    </button>

                    <button @click="fillInput('Teach me the basics of investing for a business owner with limited capital')"
                        aria-label="Ask about investment basics"
                        class="group text-left p-5 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-800/40 backdrop-blur-sm hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-lg hover:shadow-blue-100 dark:hover:shadow-blue-900/20 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:enter-delay="300">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-3 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                            </svg>
                        </div>
                        <span class="block text-sm font-bold text-gray-900 dark:text-white mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Investment basics</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400 line-clamp-2">How to start investing with limited capital.</span>
                    </button>

                    <button @click="fillInput('Walk me through a discounted cash flow (DCF) analysis with a simple example')"
                        aria-label="Ask about DCF analysis"
                        class="group text-left p-5 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-800/40 backdrop-blur-sm hover:border-orange-300 dark:hover:border-orange-600 hover:shadow-lg hover:shadow-orange-100 dark:hover:shadow-orange-900/20 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:enter-delay="400">
                        <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center mb-3 text-orange-600 dark:text-orange-400 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.315 48.315 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                            </svg>
                        </div>
                        <span class="block text-sm font-bold text-gray-900 dark:text-white mb-1 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">DCF walkthrough</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400 line-clamp-2">Understand discounted cash flow analysis.</span>
                    </button>
                </div>

            </div>

            {{-- Rendered messages list --}}
            <div class="space-y-6 max-w-3xl mx-auto">
                <template x-for="(msg, idx) in messages" :key="idx">
                    <div :class="msg.role === 'user' ? 'flex justify-end gap-3 items-end' : 'flex justify-start gap-3 items-start'" class="fade-up">

                        {{-- AI avatar --}}
                        <template x-if="msg.role !== 'user'">
                            <div class="w-9 h-9 flex-shrink-0 rounded-full bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-700 flex items-center justify-center shadow-sm mt-0.5">
                                <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"></path>
                                </svg>
                            </div>
                        </template>

                        {{-- Bubble --}}
                        <div :class="msg.role === 'user' ? 'max-w-xl order-first' : 'max-w-3xl flex-1 w-full'">
                            {{-- User bubble --}}
                            <template x-if="msg.role === 'user'">
                                <div>
                                    <div class="bg-violet-600 text-white rounded-2xl rounded-br-sm px-5 py-3.5 shadow-sm">
                                        <p class="text-[15px] whitespace-pre-wrap leading-relaxed" x-text="msg.content"></p>
                                    </div>
                                    <p class="text-[11px] text-gray-400 text-right mt-1.5 mr-1" x-text="msg.time"></p>
                                </div>
                            </template>

                            {{-- AI bubble --}}
                            <template x-if="msg.role !== 'user'">
                                <div>
                                    <div class="bg-white dark:bg-gray-800/60 border border-gray-200 dark:border-gray-800 rounded-2xl rounded-tl-sm px-6 py-5 shadow-sm">
                                        <div class="fin-prose text-gray-800 dark:text-gray-200" x-html="msg.html"></div>
                                    </div>
                                    <div class="flex items-center gap-4 mt-2 ml-1 text-gray-400">
                                        <span class="text-[11px]" x-text="msg.time"></span>
                                        <button @click="copyMsg(msg, $el)" class="text-[11px] hover:text-violet-600 dark:hover:text-violet-400 flex items-center gap-1.5 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            Copy Response
                                        </button>
                                        <button @click="regenerate(idx)" class="text-[11px] hover:text-violet-600 dark:hover:text-violet-400 flex items-center gap-1.5 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Retry
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- User avatar --}}
                        <template x-if="msg.role === 'user'">
                            <div>
                                <template x-if="userPhoto">
                                    <img :src="userPhoto" alt="User" class="w-9 h-9 flex-shrink-0 rounded-full object-cover shadow-sm ring-1 ring-black/5 dark:ring-white/10 mt-0.5">
                                </template>
                                <template x-if="!userPhoto">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm ring-1 ring-black/5 dark:ring-white/10 mt-0.5" x-text="userInitial">
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Typing indicator --}}
            <div x-show="isLoading" x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="flex justify-start gap-3 items-end mt-6 max-w-3xl mx-auto">
                <div class="w-9 h-9 flex-shrink-0 rounded-full bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-700 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-violet-600 dark:text-violet-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"></path>
                    </svg>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-violet-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                        <span class="w-2 h-2 bg-violet-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                        <span class="w-2 h-2 bg-violet-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                    </div>
                </div>
            </div>

            {{-- Error banner --}}
            <div x-show="errorMsg" x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="mt-4 flex justify-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 text-sm rounded-xl px-4 py-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-text="errorMsg"></span>
                    <button @click="errorMsg=''" class="ml-2 hover:text-red-800 dark:hover:text-red-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── INPUT AREA (PINNED BOTTOM) ── --}}
        <div class="flex-shrink-0 px-4 sm:px-6 lg:px-8 pb-5 pt-3">
            <div class="max-w-3xl mx-auto">

                {{-- Quick-topic chips (mobile & desktop) --}}
                <div class="flex gap-2 overflow-x-auto pb-3 fin-scroll no-scrollbar">
                    @foreach([
                        ['label' => 'P/E Ratio',    'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z'],
                        ['label' => 'Cash Flow',    'icon' => 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => 'DCF Model',    'icon' => 'M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.315 48.315 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18'],
                        ['label' => 'Tax Tips',     'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                        ['label' => 'KPIs',         'icon' => 'M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941'],
                        ['label' => 'Hedge Risk',   'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                        ['label' => 'Budgeting',    'icon' => 'M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5'],
                    ] as $chip)
                    <button @click="fillInput('Explain {{ $chip['label'] }} for a small business')"
                        class="flex-shrink-0 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold rounded-full border border-violet-200 dark:border-violet-700/40 text-violet-700 dark:text-violet-300 bg-violet-50/80 dark:bg-violet-500/10 hover:bg-violet-100 dark:hover:bg-violet-500/20 hover:border-violet-400 dark:hover:border-violet-500 transition-all duration-200 whitespace-nowrap group">
                        <svg class="w-3 h-3 opacity-70 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $chip['icon'] }}" />
                        </svg>
                        {{ $chip['label'] }}
                    </button>
                    @endforeach
                </div>

                {{-- Main input card --}}
                <div class="relative rounded-2xl border border-gray-200 dark:border-gray-700/80 bg-white dark:bg-gray-800/60 shadow-md transition-all duration-200 glow-focus focus-within:border-violet-400 dark:focus-within:border-violet-600 focus-within:shadow-lg focus-within:shadow-violet-500/10">

                    {{-- Textarea --}}
                    <div class="px-5 pt-4 pb-2">
                        <textarea
                            x-ref="msgInput"
                            x-model="inputText"
                            @focus="inputFocused = true"
                            @blur="inputFocused = false"
                            @keydown.enter.prevent="if(!$event.shiftKey) send()"
                            @input="autoResize($el)"
                            rows="1"
                            placeholder="Ask ShadowFinance AI anything about finance, investing, or business…"
                            :disabled="isLoading"
                            class="w-full resize-none bg-transparent text-[15px] text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none leading-relaxed max-h-52 disabled:opacity-50 transition-opacity"></textarea>
                    </div>

                    {{-- Bottom toolbar --}}
                    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-700/40">

                        {{-- Left side: char counter --}}
                        <div class="flex items-center gap-3">
                            <span x-show="inputText.length > 100" x-cloak
                                  x-transition:enter="transition ease-out duration-150"
                                  x-transition:enter-start="opacity-0 scale-95"
                                  x-transition:enter-end="opacity-100 scale-100"
                                  class="text-xs font-medium tabular-nums"
                                  :class="inputText.length > 1800 ? 'text-amber-500' : 'text-gray-400 dark:text-gray-500'"
                                  x-text="inputText.length + ' / 2000'">
                            </span>
                        </div>

                        {{-- Right side: hints + actions --}}
                        <div class="flex items-center gap-2">
                            {{-- Keyboard hint --}}
                            <span class="hidden sm:inline-flex items-center gap-1 text-[11px] text-gray-400 dark:text-gray-500 mr-1">
                                <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded text-[10px] font-medium font-sans">⇧ Enter</kbd>
                                for new line
                            </span>

                            {{-- Clear chat --}}
                            <button @click="messages = []; errorMsg = ''; history = [];"
                                    title="Clear conversation"
                                    x-show="messages.length > 0" x-cloak
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 border border-transparent hover:border-red-100 dark:hover:border-red-800/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>

                            {{-- Send button --}}
                            <button
                                @click="send()"
                                :disabled="isLoading || inputText.trim() === ''"
                                class="w-10 h-10 flex items-center justify-center rounded-full transition-all duration-200 shadow-sm disabled:shadow-none"
                                :class="isLoading || inputText.trim() === ''
                                    ? 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed'
                                    : 'bg-violet-600 hover:bg-violet-500 active:bg-violet-700 text-white hover:shadow-md hover:shadow-violet-500/25 hover:-translate-y-0.5'">
                                
                                <svg x-show="!isLoading" x-cloak class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/>
                                </svg>

                                <svg x-show="isLoading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Disclaimer --}}
                <p class="flex items-center justify-center gap-1.5 text-[11px] text-gray-400 dark:text-gray-600 mt-3">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    AI responses are for informational purposes only. Consult a licensed financial advisor before making decisions.
                </p>
            </div>
        </div>

    </div>
</div>

{{-- ══ ALPINE COMPONENT LOGIC ══ --}}
<script>
    function financeApp() {
        return {
            /* ── State ── */
            inputText: '',
            inputFocused: false,
            isLoading: false,
            errorMsg: '',
            messages: [],
            history: [],
            selectedModel: 'poolside/laguna-xs-2.1:free',
            userInitial: '{{ strtoupper(substr(auth()->user()->name ?? "U", 0, 1)) }}',
            userPhoto: '{{ auth()->user()?->profile_photo_path ? Storage::url(auth()->user()->profile_photo_path) : "" }}',
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            greeting: '',

            /* ── Init ── */
            init() {
                const h = new Date().getHours();
                this.greeting = h < 12 ? 'Good morning' : h < 17 ? 'Good afternoon' : 'Good evening';

                if (typeof marked !== 'undefined') {
                    marked.setOptions({ breaks: true, gfm: true });
                    if (typeof markedKatex !== 'undefined') {
                        marked.use(markedKatex({ throwOnError: false }));
                    }
                }
            },

            /* ── Helpers ── */
            fillInput(text) {
                this.inputText = text;
                this.$nextTick(() => {
                    this.$refs.msgInput.focus();
                    this.autoResize(this.$refs.msgInput);
                });
            },

            autoResize(el) {
                el.style.height = 'auto';
                el.style.height = Math.min(el.scrollHeight, 192) + 'px';
            },

            renderMd(text) {
                if (typeof marked !== 'undefined') return marked.parse(text);
                return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            },

            scrollDown() {
                this.$nextTick(() => {
                    const el = document.getElementById('finMessages');
                    if (el) el.scrollTop = el.scrollHeight;
                });
            },

            now() {
                return new Date().toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            },

            /* ── Send & API ── */
            async send() {
                const text = this.inputText.trim();
                if (!text || this.isLoading) return;

                const userTime = this.now();
                this.messages.push({
                    role: 'user',
                    content: text,
                    time: userTime
                });

                this.inputText = '';
                this.errorMsg = '';
                this.isLoading = true;

                if (this.$refs.msgInput) this.$refs.msgInput.style.height = 'auto';
                this.scrollDown();

                try {
                    const res = await fetch('/finance/query', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            message: text,
                            model: this.selectedModel,
                            history: this.messages.slice(0, -1).map(m => ({ role: m.role, content: m.content }))
                        })
                    });

                    const data = await res.json();

                    if (!res.ok) {
                        throw new Error(data.message || 'Error connecting to ShadowFinance AI.');
                    }

                    const aiMsg = data.reply || data.message || data.content || '';
                    this.messages.push({
                        role: 'assistant',
                        content: aiMsg,
                        html: this.renderMd(aiMsg),
                        time: this.now()
                    });
                } catch (err) {
                    this.errorMsg = err.message || 'Something went wrong. Please try again.';
                } finally {
                    this.isLoading = false;
                    this.scrollDown();
                }
            },

            copyMsg(msg, el) {
                navigator.clipboard.writeText(msg.content);
                const originalText = el.innerText;
                el.innerText = 'Copied!';
                setTimeout(() => {
                    el.innerText = originalText;
                }, 2000);
            },

            regenerate(idx) {
                if (this.isLoading) return;
                
                // Find the user message that prompted this response
                let targetIdx = -1;
                for (let i = idx; i >= 0; i--) {
                    if (this.messages[i] && this.messages[i].role === 'user') {
                        targetIdx = i;
                        break;
                    }
                }
                
                if (targetIdx !== -1) {
                    const lastUserMsg = this.messages[targetIdx].content;
                    
                    // Remove the user message and everything after it
                    this.messages.splice(targetIdx);
                    
                    this.fillInput(lastUserMsg);
                    
                    // Small delay to let UI react before sending
                    setTimeout(() => {
                        this.send();
                    }, 50);
                }
            }
        };
    }
</script>
@endsection