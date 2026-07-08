@extends('layouts.chat')

@section('title', 'Finance AI Assistant')
@section('page-title', 'Finance AI')

@section('content')

{{-- ══════════════════════════════════════════════════════════ --}}
{{--    SHADOWFINANCE AI  —  Blade + Tailwind + Alpine.js       --}}
{{-- ══════════════════════════════════════════════════════════ --}}

<style>


/* ── Pulse ring ── */
@keyframes ring-pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(16,185,129,.55); }
    50%      { box-shadow: 0 0 0 7px rgba(16,185,129,0); }
}
.ring-pulse { animation: ring-pulse 2s ease-in-out infinite; }

/* ── Entrance ── */
@keyframes fade-up {
    from { opacity:0; transform:translateY(14px); }
    to   { opacity:1; transform:translateY(0); }
}
.fade-up { animation: fade-up .45s cubic-bezier(.22,1,.36,1) both; }

/* ── Shimmer skeleton ── */
@keyframes shimmer {
    0%   { background-position: -600px 0; }
    100% { background-position: 600px 0; }
}
.skeleton {
    background: linear-gradient(90deg,#f3f4f6 25%,#e5e7eb 50%,#f3f4f6 75%);
    background-size: 600px 100%;
    animation: shimmer 1.4s infinite;
    border-radius: .5rem;
}
.dark .skeleton {
    background: linear-gradient(90deg,#1f2937 25%,#374151 50%,#1f2937 75%);
    background-size: 600px 100%;
}

/* ── Glow border on focus ── */
.glow-focus:focus-within {
    box-shadow: 0 0 0 3px rgba(139,92,246,.18), 0 0 20px rgba(139,92,246,.08);
}

/* ── Chat prose ── */
.fin-prose { font-size:.9rem; line-height:1.7; word-break:break-word; }
.fin-prose p { margin-bottom:.8rem; }
.fin-prose p:last-child { margin-bottom:0; }
.fin-prose ul { list-style-type:disc; padding-left:1.4rem; margin-bottom:.8rem; }
.fin-prose ol { list-style-type:decimal; padding-left:1.4rem; margin-bottom:.8rem; }
.fin-prose li { margin-bottom:.25rem; }
.fin-prose h1,.fin-prose h2,.fin-prose h3 { font-weight:700; margin:.9rem 0 .4rem; }
.fin-prose h2 { font-size:1.1rem; }
.fin-prose h3 { font-size:1rem; }
.fin-prose pre { background:#0f172a; border-radius:.65rem; padding:.85rem 1rem; overflow-x:auto; margin-bottom:.8rem; }
.fin-prose code { font-family:ui-monospace,monospace; font-size:.82em; background:rgba(139,92,246,.14); padding:.15em .4em; border-radius:.25rem; color:#a78bfa; }
.fin-prose pre code { background:transparent; padding:0; color:#e2e8f0; }
.fin-prose table { width:100%; border-collapse:collapse; margin-bottom:.8rem; font-size:.85rem; }
.fin-prose th,.fin-prose td { border:1px solid #e5e7eb; padding:.55rem .75rem; }
.dark .fin-prose th,.dark .fin-prose td { border-color:#374151; }
.fin-prose th { background:#f9fafb; font-weight:600; }
.dark .fin-prose th { background:#1f2937; }
.fin-prose a { color:#7c3aed; text-decoration:underline; text-underline-offset:2px; }
.dark .fin-prose a { color:#a78bfa; }
.fin-prose blockquote { border-left:3px solid #7c3aed; padding-left:1rem; color:#6b7280; font-style:italic; margin-bottom:.8rem; }

/* ── Tab underline ── */
.fin-tab[aria-selected="true"] { color:#7c3aed; border-bottom-color:#7c3aed; }
.dark .fin-tab[aria-selected="true"] { color:#a78bfa; border-bottom-color:#a78bfa; }

/* ── Scrollbar ── */
.fin-scroll::-webkit-scrollbar { width:4px; }
.fin-scroll::-webkit-scrollbar-thumb { background:#d1d5db; border-radius:99px; }
.dark .fin-scroll::-webkit-scrollbar-thumb { background:#374151; }
</style>

{{-- ══ ROOT ALPINE COMPONENT ══ --}}
<div
    class="flex flex-col h-full bg-gray-50 dark:bg-gray-950 overflow-hidden"
    x-data="financeApp()"
    x-init="init()"
>



    {{-- ── MAIN CONTENT ── --}}
    <div class="flex flex-1 min-h-0 gap-2.5 px-4 pb-4 pt-2 overflow-hidden">

        {{-- ═══ LEFT PANEL ═══ --}}
        <div class="hidden lg:flex flex-col gap-2.5 w-60 flex-shrink-0 overflow-y-auto fin-scroll">


            {{-- Tab Panel: Ask / Learn ── --}}
            <div class="bg-white dark:bg-[#0d1017] border border-gray-200 dark:border-gray-800/50 rounded-2xl shadow-sm overflow-hidden flex flex-col fade-up" style="animation-delay:180ms">

                {{-- Tab Headers --}}
                <div class="flex border-b border-gray-100 dark:border-gray-800/60" role="tablist">
                    <button
                        role="tab"
                        :aria-selected="leftTab === 'ask'"
                        @click="leftTab = 'ask'"
                        class="fin-tab flex-1 py-2.5 text-[11px] font-semibold border-b-2 border-transparent transition-colors duration-150 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                        ⚡ Quick Ask
                    </button>
                    <button
                        role="tab"
                        :aria-selected="leftTab === 'learn'"
                        @click="leftTab = 'learn'"
                        class="fin-tab flex-1 py-2.5 text-[11px] font-semibold border-b-2 border-transparent transition-colors duration-150 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                        📚 Learn
                    </button>
                </div>

                {{-- Quick Ask Tab --}}
                <div x-show="leftTab === 'ask'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="p-3 flex flex-col gap-1.5">
                    @php
                    $chips = [
                        ['e'=>'📊','t'=>'What is the P/E ratio?'],
                        ['e'=>'💰','t'=>'How to manage cash flow?'],
                        ['e'=>'📈','t'=>'Explain compound interest'],
                        ['e'=>'🏦','t'=>'How does a DCF work?'],
                        ['e'=>'💼','t'=>'Small biz tax strategies'],
                        ['e'=>'📉','t'=>'What is a bear market?'],
                        ['e'=>'🔐','t'=>'How to hedge financial risk?'],
                        ['e'=>'💡','t'=>'Best KPIs for my business'],
                    ];
                    @endphp
                    @foreach($chips as $c)
                    <button
                        @click="fillInput('{{ addslashes($c['t']) }}')"
                        class="group text-left flex items-center gap-2 text-[11px] px-2.5 py-2 rounded-xl bg-gray-50 dark:bg-gray-800/50 text-gray-600 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-violet-500/10 hover:text-violet-700 dark:hover:text-violet-300 transition-all duration-150 border border-transparent hover:border-violet-100 dark:hover:border-violet-800/40">
                        <span class="text-sm leading-none">{{ $c['e'] }}</span>
                        <span class="truncate">{{ $c['t'] }}</span>
                    </button>
                    @endforeach
                </div>

                {{-- Learn Tab --}}
                <div x-show="leftTab === 'learn'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="p-3 flex flex-col gap-1.5">
                    @php
                    $modules = [
                        ['e'=>'🎯','t'=>'Budgeting 101',           'q'=>'Teach me Business Budgeting 101 step by step with examples'],
                        ['e'=>'💧','t'=>'Cash Flow',                'q'=>'Explain how to manage cash flow for a small business step by step'],
                        ['e'=>'📊','t'=>'Investment Basics',        'q'=>'Teach me investing basics for business owners — risk, return, diversification'],
                        ['e'=>'📋','t'=>'Reading Financials',       'q'=>'How do I read a balance sheet and income statement? Explain with an example.'],
                        ['e'=>'⚖️','t'=>'Break-Even Analysis',      'q'=>'Explain break-even analysis with a practical example and formula'],
                        ['e'=>'🏢','t'=>'Business Valuation',       'q'=>'How do I value my small business? Explain DCF, multiples, and asset methods'],
                        ['e'=>'📉','t'=>'Risk Management',          'q'=>'What are the key financial risk management strategies for small businesses?'],
                        ['e'=>'💹','t'=>'Financial Ratios',         'q'=>'Explain the most important financial ratios: P/E, ROE, ROA, current ratio'],
                    ];
                    @endphp
                    @foreach($modules as $m)
                    <button
                        @click="fillInput('{{ addslashes($m['q']) }}')"
                        class="group text-left flex items-center gap-2 text-[11px] px-2.5 py-2 rounded-xl bg-violet-50 dark:bg-violet-500/10 text-violet-700 dark:text-violet-300 hover:bg-violet-100 dark:hover:bg-violet-500/20 transition-all duration-150 border border-transparent hover:border-violet-200 dark:hover:border-violet-700/40">
                        <span class="text-sm leading-none">{{ $m['e'] }}</span>
                        <span class="truncate">{{ $m['t'] }}</span>
                        <svg class="w-3 h-3 ml-auto opacity-0 group-hover:opacity-100 flex-shrink-0 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Tips Widget --}}
            <div class="bg-gradient-to-br from-violet-600 to-purple-700 rounded-2xl p-4 shadow-sm fade-up" style="animation-delay:260ms">
                <p class="text-[11px] font-bold text-violet-200 uppercase tracking-wide mb-1">💡 Finance Tip</p>
                <p class="text-xs text-white/90 leading-relaxed" x-text="currentTip"></p>
                <button @click="rotateTip()" class="mt-2.5 text-[10px] font-semibold text-violet-300 hover:text-white flex items-center gap-1 transition-colors">
                    Next tip
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </button>
            </div>
        </div>

        {{-- ═══ MAIN CHAT PANEL ═══ --}}
        <div class="flex flex-col flex-1 min-w-0 min-h-0 bg-white dark:bg-[#0d1017] border border-gray-200 dark:border-gray-800/50 rounded-2xl shadow-sm overflow-hidden">

            {{-- Chat Header --}}
            <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-100 dark:border-gray-800/60 flex-shrink-0 bg-white/80 dark:bg-[#0d1017]/90 backdrop-blur-sm">
                <div class="relative">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-violet-500 via-purple-500 to-emerald-500 flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 border-2 border-white dark:border-[#0d1017] rounded-full ring-pulse"></span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">ShadowFinance AI</p>
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-1.5 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Online
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">Finance · Business Coaching · Market Insights</p>
                </div>

                {{-- Model selector in header (desktop) --}}
                <div class="hidden sm:flex items-center gap-2">
                    <div class="relative">
                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3 h-3 text-violet-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <select x-model="selectedModel"
                                class="pl-7 pr-6 py-1.5 text-[11px] font-medium bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500/30 appearance-none cursor-pointer transition-all">
                            <option value="poolside/laguna-xs-2.1:free">Laguna XS 2.1</option>
                            <option value="cohere/north-mini-code:free">Cohere North Mini</option>
                            <option value="nvidia/nemotron-3-ultra-550b-a55b:free">Nemotron Ultra 550B</option>
                        </select>
                        <svg class="absolute right-1.5 top-1/2 -translate-y-1/2 w-3 h-3 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>

                    {{-- Clear chat --}}
                    <button @click="clearChat()" title="Clear conversation"
                            class="p-2 text-gray-400 hover:text-red-500 dark:hover:text-red-400 rounded-xl hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>

            {{-- ── MESSAGES ── --}}
            <div id="finMessages"
                 class="flex-1 overflow-y-auto px-5 py-5 fin-scroll"
                 style="scroll-behavior:smooth">

                {{-- Welcome state --}}
                <div x-show="messages.length === 0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="flex flex-col items-center justify-center h-full min-h-[280px] text-center px-4 py-8 fade-up">
                    <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-violet-500 via-purple-500 to-emerald-500 flex items-center justify-center mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1.5">ShadowFinance AI</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mb-6 leading-relaxed">
                        Your personal AI finance assistant. Ask me anything about business finance, investing, markets, or financial education.
                    </p>
                    {{-- Starter grid --}}
                    <div class="grid grid-cols-2 gap-2 w-full max-w-md">
                        @foreach([
                            ['e'=>'📊','t'=>'Explain P/E ratio','q'=>'What is the Price-to-Earnings ratio and how do I use it to evaluate stocks?'],
                            ['e'=>'💰','t'=>'Cash flow tips','q'=>'What are the best strategies to manage cash flow for a small business?'],
                            ['e'=>'📈','t'=>'Investment basics','q'=>'Teach me the basics of investing for a business owner with limited capital'],
                            ['e'=>'🏦','t'=>'DCF walkthrough','q'=>'Walk me through a discounted cash flow (DCF) analysis with a simple example'],
                        ] as $s)
                        <button
                            @click="fillInput('{{ addslashes($s['q']) }}')"
                            class="group text-left p-3 rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50 hover:border-violet-200 dark:hover:border-violet-700/50 hover:bg-violet-50 dark:hover:bg-violet-500/10 transition-all duration-200">
                            <span class="text-xl block mb-1">{{ $s['e'] }}</span>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 group-hover:text-violet-700 dark:group-hover:text-violet-300 transition-colors">{{ $s['t'] }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Rendered messages --}}
                <div class="space-y-5">
                    <template x-for="(msg, idx) in messages" :key="idx">
                        <div :class="msg.role === 'user' ? 'flex justify-end gap-3 items-end' : 'flex justify-start gap-3 items-start'"
                             class="fade-up">

                            {{-- AI avatar --}}
                            <template x-if="msg.role !== 'user'">
                                <div class="w-8 h-8 rounded-2xl bg-gradient-to-br from-violet-500 via-purple-500 to-emerald-500 flex items-center justify-center flex-shrink-0 shadow-sm mt-0.5">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </template>

                            {{-- Bubble --}}
                            <div :class="msg.role === 'user' ? 'max-w-xl order-first' : 'max-w-3xl flex-1'">
                                {{-- User bubble --}}
                                <template x-if="msg.role === 'user'">
                                    <div>
                                        <div class="bg-gradient-to-br from-violet-600 to-violet-700 text-white rounded-2xl rounded-br-sm px-4 py-3 shadow-sm">
                                            <p class="text-sm whitespace-pre-wrap leading-relaxed" x-text="msg.content"></p>
                                        </div>
                                        <p class="text-[10px] text-gray-400 text-right mt-1 mr-1" x-text="msg.time"></p>
                                    </div>
                                </template>

                                {{-- AI bubble --}}
                                <template x-if="msg.role !== 'user'">
                                    <div>
                                        <div class="bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700/60 rounded-2xl rounded-tl-sm px-5 py-4 shadow-sm">
                                            {{-- Render markdown HTML --}}
                                            <div class="fin-prose text-gray-800 dark:text-gray-200" x-html="msg.html"></div>
                                        </div>
                                        <div class="flex items-center gap-3 mt-1.5 ml-1">
                                            <span class="text-[10px] text-gray-400" x-text="msg.time"></span>
                                            {{-- Copy button --}}
                                            <button @click="copyMsg(msg, $el)"
                                                    class="text-[10px] text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 flex items-center gap-1 transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                Copy
                                            </button>
                                            {{-- Regenerate hint --}}
                                            <button @click="regenerate(idx)"
                                                    class="text-[10px] text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 flex items-center gap-1 transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Retry
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- User avatar --}}
                            <template x-if="msg.role === 'user'">
                                <div class="w-8 h-8 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm"
                                     x-text="userInitial">
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Typing indicator --}}
                <div x-show="isLoading"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="flex justify-start gap-3 items-end mt-5">
                    <div class="w-8 h-8 rounded-2xl bg-gradient-to-br from-violet-500 via-purple-500 to-emerald-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700/60 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-violet-400 animate-bounce" style="animation-delay:0ms"></span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-bounce" style="animation-delay:140ms"></span>
                            <span class="w-2 h-2 rounded-full bg-violet-400 animate-bounce" style="animation-delay:280ms"></span>
                        </div>
                    </div>
                </div>

                {{-- Error banner --}}
                <div x-show="errorMsg"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-4 flex justify-center">
                    <div class="inline-flex items-center gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 text-xs rounded-2xl px-4 py-2.5">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="errorMsg"></span>
                        <button @click="errorMsg=''" class="ml-1 hover:text-red-800 dark:hover:text-red-300">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── INPUT AREA ── --}}
            <div class="flex-shrink-0 border-t border-gray-100 dark:border-gray-800/60 bg-white/90 dark:bg-[#0d1017]/90 backdrop-blur-sm px-4 py-3">

                {{-- Mobile quick chips --}}
                <div class="flex gap-2 overflow-x-auto pb-2 mb-2 fin-scroll lg:hidden">
                    @foreach(['P/E Ratio','Cash Flow','DCF Model','Tax Tips','KPIs','Hedge Risk','Budgeting'] as $chip)
                    <button @click="fillInput('Explain {{ $chip }} for a small business')"
                            class="flex-shrink-0 px-3 py-1 text-[11px] font-medium rounded-full border border-violet-200 dark:border-violet-700/40 text-violet-700 dark:text-violet-300 bg-violet-50 dark:bg-violet-500/10 hover:bg-violet-100 dark:hover:bg-violet-500/20 transition-colors whitespace-nowrap">
                        {{ $chip }}
                    </button>
                    @endforeach
                </div>

                {{-- Input box --}}
                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 transition-all duration-200 glow-focus shadow-sm"
                     :class="{ 'border-violet-300 dark:border-violet-700': inputFocused }">
                    <div class="px-4 pt-3 pb-1">
                        <textarea
                            x-ref="msgInput"
                            x-model="inputText"
                            @focus="inputFocused = true"
                            @blur="inputFocused = false"
                            @keydown.enter.prevent="if(!$event.shiftKey) send()"
                            @input="autoResize($el)"
                            rows="1"
                            placeholder="Ask about finance, markets, investing, or business…"
                            :disabled="isLoading"
                            class="w-full resize-none bg-transparent text-sm text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none leading-relaxed max-h-36 disabled:opacity-60"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-between px-3 py-2 border-t border-gray-100 dark:border-gray-800">
                        {{-- Left: char count + model (mobile) --}}
                        <div class="flex items-center gap-2">
                            {{-- mobile model select --}}
                            <div class="relative sm:hidden">
                                <select x-model="selectedModel"
                                        class="pl-2 pr-5 py-1 text-[10px] bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg focus:outline-none appearance-none cursor-pointer">
                                    <option value="poolside/laguna-xs-2.1:free">Laguna XS</option>
                                    <option value="cohere/north-mini-code:free">Cohere</option>
                                    <option value="nvidia/nemotron-3-ultra-550b-a55b:free">Nemotron</option>
                                </select>
                                <svg class="absolute right-1 top-1/2 -translate-y-1/2 w-2.5 h-2.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>

                            <span class="text-[10px] text-gray-400"
                                  :class="inputText.length > 1800 ? 'text-amber-500 font-medium' : ''"
                                  x-show="inputText.length > 100"
                                  x-text="inputText.length + ' / 2000'">
                            </span>
                        </div>

                        {{-- Right: hint + send --}}
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] text-gray-400 hidden sm:inline-flex items-center gap-1">
                                <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded font-sans text-[10px]">Enter</kbd> send &nbsp;·&nbsp;
                                <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded font-sans text-[10px]">Shift+Enter</kbd> newline
                            </span>
                            <button
                                @click="send()"
                                :disabled="isLoading || inputText.trim() === ''"
                                class="w-8 h-8 flex items-center justify-center rounded-full text-white transition-all duration-200 shadow-sm hover:shadow-md"
                                :class="isLoading || inputText.trim() === ''
                                    ? 'bg-gray-200 dark:bg-gray-700 cursor-not-allowed'
                                    : 'bg-gradient-to-br from-violet-600 to-emerald-500 hover:from-violet-500 hover:to-emerald-400 hover:scale-105 active:scale-95'">
                                <svg x-show="!isLoading" class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/>
                                </svg>
                                <svg x-show="isLoading" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <p class="text-[10px] text-center text-gray-400 dark:text-gray-600 mt-2">
                    AI responses are for informational purposes only. Consult a licensed financial advisor before making financial decisions.
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
        leftTab:      'ask',
        inputText:    '',
        inputFocused: false,
        isLoading:    false,
        errorMsg:     '',
        messages:     [],
        history:      [],
        selectedModel:'poolside/laguna-xs-2.1:free',
        userInitial:  '{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}',
        userPhoto:    '{{ auth()->user()->profile_photo_path ? Storage::url(auth()->user()->profile_photo_path) : '' }}',
        csrfToken:    document.querySelector('meta[name="csrf-token"]')?.content ?? '',

        currentTipIdx: 0,
        tips: [
            'The 50/30/20 rule: 50% needs, 30% wants, 20% savings — works for businesses too.',
            'Always track your burn rate. If revenue stops today, how many months can you operate?',
            'Compound interest works both ways — on your savings AND your debt.',
            'A DCF valuation is only as good as its assumptions. Always stress-test your projections.',
            'Cash flow ≠ Profit. A profitable business can still go bankrupt from poor cash management.',
            'The best hedge against inflation is productive assets: real estate, equities, and skills.',
            'Revenue is vanity, profit is sanity, cash flow is reality.',
            'Diversification doesn\'t eliminate risk — it just prevents a single event from destroying you.',
        ],

        /* ── Init ── */
        init() {
            this.currentTip = this.tips[0];
            if (typeof marked !== 'undefined') {
                marked.setOptions({ breaks: true, gfm: true });
                if (typeof markedKatex !== 'undefined') {
                    marked.use(markedKatex({ throwOnError: false }));
                }
            }
        },

        /* ── Tips ── */
        currentTip: '',
        rotateTip() {
            this.currentTipIdx = (this.currentTipIdx + 1) % this.tips.length;
            this.currentTip = this.tips[this.currentTipIdx];
        },

        /* ── Input ── */
        fillInput(text) {
            this.inputText = text;
            this.$nextTick(() => {
                this.$refs.msgInput.focus();
                this.autoResize(this.$refs.msgInput);
            });
        },

        autoResize(el) {
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight, 144) + 'px';
        },

        /* ── Render markdown ── */
        renderMd(text) {
            if (typeof marked !== 'undefined') return marked.parse(text);
            return text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        },

        /* ── Scroll ── */
        scrollDown() {
            this.$nextTick(() => {
                const el = document.getElementById('finMessages');
                if (el) el.scrollTop = el.scrollHeight;
            });
        },

        /* ── Now ── */
        now() {
            return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        },

        /* ── Send ── */
        async send() {
            const text = this.inputText.trim();
            if (!text || this.isLoading) return;

            this.inputText = '';
            this.$nextTick(() => this.autoResize(this.$refs.msgInput));
            this.errorMsg  = '';

            // Push user message
            this.messages.push({ role: 'user', content: text, time: this.now(), html: '' });
            this.history.push({ role: 'user', content: text });
            this.scrollDown();

            this.isLoading = true;

            try {
                const res = await fetch('{{ route("finance.query") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message: text,
                        model:   this.selectedModel,
                        history: this.history.slice(-12),
                    }),
                });

                if (!res.ok) throw new Error('Server error ' + res.status);
                const data = await res.json();

                if (data.success) {
                    this.history.push({ role: 'assistant', content: data.message });
                    this.messages.push({
                        role:    'assistant',
                        content: data.message,
                        html:    this.renderMd(data.message),
                        time:    this.now(),
                    });
                    this.scrollDown();
                    // Highlight code blocks after render
                    this.$nextTick(() => {
                        if (typeof hljs !== 'undefined') {
                            document.querySelectorAll('#finMessages pre code').forEach(el => {
                                if (!el.dataset.highlighted) hljs.highlightElement(el);
                            });
                        }
                    });
                } else {
                    this.errorMsg = data.error || 'Something went wrong. Please try again.';
                }
            } catch(e) {
                this.errorMsg = 'Network error: ' + e.message;
            } finally {
                this.isLoading = false;
                this.$nextTick(() => this.$refs.msgInput.focus());
                this.scrollDown();
            }
        },

        /* ── Regenerate ── */
        async regenerate(idx) {
            // Find the user message before this AI message
            const userMsg = this.messages.slice(0, idx).reverse().find(m => m.role === 'user');
            if (!userMsg) return;
            // Remove all messages from idx onwards
            this.messages.splice(idx, this.messages.length - idx);
            this.history.splice(idx, this.history.length - idx);
            this.inputText = userMsg.content;
            await this.send();
        },

        /* ── Copy ── */
        copyMsg(msg, btn) {
            navigator.clipboard.writeText(msg.content).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = `<svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Copied!`;
                btn.classList.add('text-emerald-500');
                setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('text-emerald-500'); }, 2000);
            });
        },

        /* ── Clear ── */
        clearChat() {
            this.messages = [];
            this.history  = [];
            this.errorMsg = '';
            this.$nextTick(() => this.$refs.msgInput.focus());
        },
    };
}
</script>

@endsection
