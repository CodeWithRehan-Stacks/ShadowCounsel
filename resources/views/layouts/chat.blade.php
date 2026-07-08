<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: {{ auth()->user()->setting?->dark_mode ? 'true' : 'false' }}, sidebarOpen: true, mobileSidebarOpen: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AI Chat') — {{ config('app.name') }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- marked.js for Markdown -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <!-- KaTeX for Math rendering -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked-katex-extension@3.1.5/lib/index.umd.js"></script>
    <!-- Highlight.js for Syntax Highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/tokyo-night-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #374151; }
        pre { border-radius: 0.5rem; overflow-x: auto; }
        /* Markdown Rendering Styles */
        .chat-bubble { font-size: 0.95rem; line-height: 1.6; word-break: break-word; }
        .chat-bubble p { margin-bottom: 1rem; }
        .chat-bubble p:last-child { margin-bottom: 0; }
        .chat-bubble ul { list-style-type: disc; margin-bottom: 1rem; padding-left: 1.5rem; }
        .chat-bubble ol { list-style-type: decimal; margin-bottom: 1rem; padding-left: 1.5rem; }
        .chat-bubble li { margin-bottom: 0.25rem; }
        .chat-bubble h1, .chat-bubble h2, .chat-bubble h3, .chat-bubble h4 { font-weight: 600; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #111827; }
        .dark .chat-bubble h1, .dark .chat-bubble h2, .dark .chat-bubble h3, .dark .chat-bubble h4 { color: #f3f4f6; }
        .chat-bubble h1 { font-size: 1.5rem; }
        .chat-bubble h2 { font-size: 1.25rem; }
        .chat-bubble h3 { font-size: 1.125rem; }
        .chat-bubble pre { background-color: #1a1b26; color: #a9b1d6; padding: 1rem; border-radius: 0.75rem; overflow-x: auto; margin-bottom: 1rem; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 0.875rem; line-height: 1.5; border: 1px solid #e5e7eb; }
        .dark .chat-bubble pre { border: 1px solid #272a38; }
        .chat-bubble code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 0.85em; background-color: rgba(156, 163, 175, 0.2); padding: 0.2em 0.4em; border-radius: 0.25rem; color: #c026d3; }
        .dark .chat-bubble code { color: #e879f9; background-color: rgba(156, 163, 175, 0.15); }
        .chat-bubble pre code { background-color: transparent; padding: 0; border-radius: 0; color: inherit; }
        .chat-bubble blockquote { border-left: 4px solid #e5e7eb; padding-left: 1rem; color: #6b7280; margin-bottom: 1rem; font-style: italic; }
        .dark .chat-bubble blockquote { border-left-color: #374151; color: #9ca3af; }
        .chat-bubble table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; font-size: 0.875rem; }
        .chat-bubble th, .chat-bubble td { border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; }
        .dark .chat-bubble th, .dark .chat-bubble td { border-color: #374151; }
        .chat-bubble th { background-color: #f9fafb; font-weight: 600; }
        .dark .chat-bubble th { background-color: #1f2937; }
        .chat-bubble a { color: #7c3aed; text-decoration: underline; text-underline-offset: 2px; }
        .dark .chat-bubble a { color: #a78bfa; }
    </style>
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen selection:bg-violet-500/30">

<div class="flex h-screen overflow-hidden">

    <!-- ===================== SIDEBAR ===================== -->
    <aside :class="sidebarOpen ? 'w-[280px]' : 'w-0 overflow-hidden opacity-0'"
           class="hidden md:flex flex-col flex-shrink-0 bg-gray-50 dark:bg-[#0f1117] border-r border-gray-200 dark:border-gray-800/60 transition-all duration-300 ease-in-out relative z-20">

        <!-- Sidebar Header (Logo & Toggle) -->
        <div class="h-16 px-5 flex items-center justify-between shrink-0">
            <a href="{{ route('chat.index') }}" class="flex items-center group">
                <img src="/svg/Logo.svg" alt="ShadowCounsel" class="h-7 w-auto transition-transform group-hover:scale-[1.02]">
            </a>
            <!-- Keep empty div for spacing if toggle is moved, or add a generic close icon for mobile, but this is desktop -->
        </div>

        <!-- Search & New Chat -->
        <div class="px-4 pb-4 space-y-3 shrink-0">
            <a href="{{ route('chat.index') }}" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-medium bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-700/60 hover:border-violet-300 dark:hover:border-violet-500/50 hover:bg-violet-50 dark:hover:bg-violet-500/10 text-gray-700 dark:text-gray-200 rounded-xl transition duration-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Chat
            </a>

            <div class="relative group">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-violet-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" id="sidebarSearch" placeholder="Search chats..."
                       class="w-full pl-9 pr-3 py-2 text-sm bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-700/60 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 placeholder-gray-400 dark:placeholder-gray-500 transition-all text-gray-700 dark:text-gray-200 shadow-sm">
            </div>
        </div>

        <!-- Chat History -->
        <div class="flex-grow overflow-y-auto px-3 py-2 space-y-0.5" id="chatHistoryList">
            @php
                $sidebarChats = auth()->user()->chats()->orderBy('updated_at', 'desc')->get();
                $currentChatId = isset($chat) ? $chat->id : null;
            @endphp

            @forelse($sidebarChats as $sidebarChat)
                <div class="chat-history-item" data-title="{{ strtolower($sidebarChat->title) }}">
                    <a href="{{ route('chat.show', $sidebarChat) }}"
                       class="group relative flex items-center px-3 py-2.5 rounded-xl text-sm transition-all {{ $currentChatId == $sidebarChat->id ? 'bg-gray-200/60 dark:bg-[#1a1d24] text-gray-900 dark:text-white font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200/40 dark:hover:bg-[#1a1d24] hover:text-gray-900 dark:hover:text-gray-200' }}">
                        
                        <span class="truncate pr-6 relative z-10">{{ $sidebarChat->title ?? 'New Chat' }}</span>
                        
                        <!-- Fade gradient to prevent text overlapping icon -->
                        <div class="absolute right-0 inset-y-0 w-12 bg-gradient-to-l {{ $currentChatId == $sidebarChat->id ? 'from-gray-100 dark:from-[#1a1d24]' : 'from-transparent group-hover:from-gray-50 dark:group-hover:from-[#1a1d24]' }} to-transparent rounded-r-xl pointer-events-none"></div>

                        <form action="{{ route('chat.destroy', $sidebarChat) }}" method="POST" class="absolute right-2 opacity-0 group-hover:opacity-100 transition-opacity z-20"
                              onsubmit="return confirm('Delete this chat?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 transition-colors" title="Delete Chat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </a>
                </div>
            @empty
                <div class="text-center py-8 px-4">
                    <p class="text-xs text-gray-500 dark:text-gray-500">No chats yet.</p>
                </div>
            @endforelse
        </div>

        <!-- Sidebar Bottom Nav -->
        <div class="p-3 border-t border-gray-200 dark:border-gray-800/60 space-y-0.5 shrink-0">
            <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('chat.*') ? 'bg-gray-200/60 dark:bg-[#1a1d24] text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200/40 dark:hover:bg-[#1a1d24] hover:text-gray-900 dark:hover:text-gray-200' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Chat
            </a>
            <a href="{{ route('finance.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('finance.*') ? 'bg-gray-200/60 dark:bg-[#1a1d24] text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200/40 dark:hover:bg-[#1a1d24] hover:text-gray-900 dark:hover:text-gray-200' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Finance AI
            </a>
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('profile.*') ? 'bg-gray-200/60 dark:bg-[#1a1d24] text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200/40 dark:hover:bg-[#1a1d24] hover:text-gray-900 dark:hover:text-gray-200' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profile
            </a>
            <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('settings.*') ? 'bg-gray-200/60 dark:bg-[#1a1d24] text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200/40 dark:hover:bg-[#1a1d24] hover:text-gray-900 dark:hover:text-gray-200' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
            <form action="{{ route('logout') }}" method="POST" class="pt-1">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-red-500/90 dark:text-red-400/80 hover:text-red-600 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-colors text-left">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" style="display: none;"
         class="fixed inset-0 bg-gray-900/40 dark:bg-black/60 backdrop-blur-sm z-30 md:hidden" x-transition.opacity></div>

    <!-- Mobile Sidebar Panel -->
    <div :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
         class="fixed top-0 left-0 z-40 h-full w-[280px] flex flex-col bg-white dark:bg-[#0f1117] transition-transform duration-300 md:hidden shadow-2xl">
        <div class="h-16 px-5 flex items-center justify-between border-b border-gray-200 dark:border-gray-800/60 shrink-0">
            <a href="{{ route('chat.index') }}" class="flex items-center">
                <img src="/svg/Logo.svg" alt="ShadowCounsel" class="h-7 w-auto">
            </a>
            <button @click="mobileSidebarOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="px-4 py-4 space-y-3 border-b border-gray-200 dark:border-gray-800/60 shrink-0">
            <a href="{{ route('chat.index') }}" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-medium bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-700/60 text-gray-700 dark:text-gray-200 rounded-xl shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Chat
            </a>
        </div>

        <div class="flex-grow overflow-y-auto px-3 py-2 space-y-0.5">
            @forelse($sidebarChats ?? [] as $sidebarChat)
                <div class="px-3 py-0.5">
                    <a href="{{ route('chat.show', $sidebarChat) }}" @click="mobileSidebarOpen = false"
                       class="flex items-center px-3 py-2.5 rounded-xl text-sm transition-colors {{ (isset($chat) && $chat->id == $sidebarChat->id) ? 'bg-gray-100 dark:bg-[#1a1d24] text-gray-900 dark:text-white font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#1a1d24]' }}">
                        <span class="truncate">{{ $sidebarChat->title ?? 'New Chat' }}</span>
                    </a>
                </div>
            @empty
                <p class="text-xs text-gray-400 text-center py-6 px-3">No chats yet.</p>
            @endforelse
        </div>
        <div class="p-3 border-t border-gray-200 dark:border-gray-800/60 space-y-0.5 shrink-0">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#1a1d24] rounded-xl transition-colors">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profile
            </a>
            <a href="{{ route('finance.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('finance.*') ? 'bg-gray-200/60 dark:bg-[#1a1d24] text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#1a1d24]' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Finance AI
            </a>
            <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#1a1d24] rounded-xl transition-colors">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
            <form action="{{ route('logout') }}" method="POST" class="pt-1">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-red-500/90 dark:text-red-400/80 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-colors text-left">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- ===================== MAIN AREA ===================== -->
    <div class="flex flex-col flex-grow overflow-hidden relative bg-white dark:bg-gray-950">

        <!-- Top Navbar -->
        <header class="flex-shrink-0 h-16 flex items-center justify-between px-4 lg:px-6 border-b border-gray-200 dark:border-gray-800/60 z-10 transition-all">
            <div class="flex items-center gap-3">
                
                <!-- Toggle Sidebar (Always visible unless mobile where it opens mobile sidebar) -->
                <button @click="sidebarOpen = !sidebarOpen" class="hidden md:flex text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#1a1d24]" title="Toggle Sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <!-- Mobile Hamburger -->
                <button @click="mobileSidebarOpen = true" class="md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#1a1d24]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <div class="h-5 w-px bg-gray-200 dark:bg-gray-800 mx-1 hidden md:block"></div>

                <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate max-w-[200px] sm:max-w-xs">@yield('page-title', 'AI Chat')</span>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Model Selector (visible only on chat page) -->
                @yield('navbar-extras')

                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-[#1a1d24] transition-colors" title="Toggle Dark Mode">
                    <template x-if="darkMode">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </template>
                    <template x-if="!darkMode">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </template>
                </button>

                <!-- User Avatar -->
                <a href="{{ route('profile.index') }}" class="flex items-center gap-2 group pl-2 border-l border-gray-200 dark:border-gray-800">
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-transparent group-hover:ring-violet-500/50 transition-all">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold ring-2 ring-transparent group-hover:ring-violet-500/50 transition-all shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="absolute top-20 left-1/2 -translate-x-1/2 z-50 px-4 py-2.5 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 text-sm font-medium rounded-xl shadow-xl flex items-center gap-3">
                <svg class="w-4 h-4 text-green-400 dark:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Page Content -->
        <main class="flex-grow overflow-hidden bg-white dark:bg-gray-950">
            @yield('content')
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const search = document.getElementById('sidebarSearch');
        if (search) {
            search.addEventListener('input', function () {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.chat-history-item').forEach(function (el) {
                    el.style.display = el.dataset.title.includes(q) ? 'block' : 'none';
                });
            });
        }
    });
</script>
</body>
</html>