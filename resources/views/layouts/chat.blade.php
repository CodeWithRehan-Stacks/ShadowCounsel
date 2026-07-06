<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: {{ auth()->user()->setting?->dark_mode ? 'true' : 'false' }}, sidebarOpen: true, mobileSidebarOpen: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AI Chat') — {{ config('app.name') }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- marked.js for Markdown -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #6b7280; }
        pre { border-radius: 0.5rem; overflow-x: auto; }
        .chat-bubble p { margin: 0; }
        .chat-bubble ul, .chat-bubble ol { padding-left: 1.5rem; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen">

<div class="flex h-screen overflow-hidden">

    <!-- ===================== SIDEBAR ===================== -->
    <aside :class="sidebarOpen ? 'w-72' : 'w-0 overflow-hidden'"
           class="hidden md:flex flex-col flex-shrink-0 bg-gray-900 dark:bg-gray-950 transition-all duration-300 ease-in-out">

        <!-- Sidebar Header -->
        <div class="p-4 flex items-center justify-between border-b border-gray-700">
            <a href="{{ route('chat.index') }}" class="flex items-center">
                <img src="/svg/Logo.svg" alt="ShadowCounsel" class="h-7 w-auto ">
            </a>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-white transition">
                <i class="bi bi-layout-sidebar-inset-reverse text-xl"></i>
            </button>
        </div>

        <!-- New Chat Button -->
        <div class="p-3 border-b border-gray-700">
            <a href="{{ route('chat.index') }}" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm font-medium bg-violet-600 hover:bg-violet-700 text-white rounded-xl transition duration-200">
                <i class="bi bi-plus-lg"></i>
                New Chat
            </a>
        </div>

        <!-- Search -->
        <div class="p-3 border-b border-gray-700">
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="sidebarSearch" placeholder="Search chats..." class="w-full pl-8 pr-3 py-2 text-sm bg-gray-800 text-gray-200 border border-gray-700 rounded-lg focus:outline-none focus:ring-1 focus:ring-violet-500 placeholder-gray-500">
            </div>
        </div>

        <!-- Chat History -->
        <div class="flex-grow overflow-y-auto py-2" id="chatHistoryList">
            @php
                $sidebarChats = auth()->user()->chats()->orderBy('updated_at', 'desc')->get();
                $currentChatId = isset($chat) ? $chat->id : null;
            @endphp

            @forelse($sidebarChats as $sidebarChat)
                <div class="chat-history-item px-3 py-1" data-title="{{ strtolower($sidebarChat->title) }}">
                    <a href="{{ route('chat.show', $sidebarChat) }}"
                       class="group flex items-center justify-between px-3 py-2 rounded-lg text-sm text-gray-300 hover:bg-gray-800 transition {{ $currentChatId == $sidebarChat->id ? 'bg-gray-800 text-white' : '' }}">
                        <span class="truncate flex-1">{{ $sidebarChat->title ?? 'New Chat' }}</span>
                        <form action="{{ route('chat.destroy', $sidebarChat) }}" method="POST" class="ml-2 hidden group-hover:block"
                              onsubmit="return confirm('Delete this chat?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-500 hover:text-red-400 transition">
                                <i class="bi bi-trash3 text-xs"></i>
                            </button>
                        </form>
                    </a>
                </div>
            @empty
                <p class="text-xs text-gray-500 text-center py-4 px-3">No chats yet. Start a new conversation!</p>
            @endforelse
        </div>

        <!-- Sidebar Nav Links -->
        <div class="p-3 border-t border-gray-700 space-y-1">
            <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 hover:text-white rounded-xl transition {{ request()->routeIs('chat.*') ? 'bg-gray-800 text-white' : '' }}">
                <i class="bi bi-chat-dots w-4"></i> Chat
            </a>
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 hover:text-white rounded-xl transition {{ request()->routeIs('profile.*') ? 'bg-gray-800 text-white' : '' }}">
                <i class="bi bi-person-circle w-4"></i> Profile
            </a>
            <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 hover:text-white rounded-xl transition {{ request()->routeIs('settings.*') ? 'bg-gray-800 text-white' : '' }}">
                <i class="bi bi-gear w-4"></i> Settings
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 rounded-xl transition text-left">
                    <i class="bi bi-box-arrow-left w-4"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-20 md:hidden" x-transition.opacity></div>

    <!-- Mobile Sidebar Panel -->
    <div :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
         class="fixed top-0 left-0 z-30 h-full w-72 flex flex-col bg-gray-900 transition-transform duration-300 md:hidden">
        <div class="p-4 flex items-center justify-between border-b border-gray-700">
            <a href="{{ route('chat.index') }}" class="flex items-center">
                <img src="/svg/Logo.svg" alt="ShadowCounsel" class="h-7 w-auto brightness-0 invert">
            </a>
            <button @click="mobileSidebarOpen = false" class="text-gray-400 hover:text-white">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div class="p-3 border-b border-gray-700">
            <a href="{{ route('chat.index') }}" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm font-medium bg-violet-600 hover:bg-violet-700 text-white rounded-xl transition">
                <i class="bi bi-plus-lg"></i> New Chat
            </a>
        </div>
        <div class="flex-grow overflow-y-auto py-2">
            @forelse($sidebarChats ?? [] as $sidebarChat)
                <div class="px-3 py-1">
                    <a href="{{ route('chat.show', $sidebarChat) }}" @click="mobileSidebarOpen = false"
                       class="flex items-center px-3 py-2 rounded-lg text-sm text-gray-300 hover:bg-gray-800 transition {{ (isset($chat) && $chat->id == $sidebarChat->id) ? 'bg-gray-800 text-white' : '' }}">
                        <span class="truncate">{{ $sidebarChat->title ?? 'New Chat' }}</span>
                    </a>
                </div>
            @empty
                <p class="text-xs text-gray-500 text-center py-4 px-3">No chats yet.</p>
            @endforelse
        </div>
        <div class="p-3 border-t border-gray-700 space-y-1">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 rounded-xl transition">
                <i class="bi bi-person-circle"></i> Profile
            </a>
            <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 rounded-xl transition">
                <i class="bi bi-gear"></i> Settings
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 rounded-xl transition text-left">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- ===================== MAIN AREA ===================== -->
    <div class="flex flex-col flex-grow overflow-hidden">

        <!-- Top Navbar -->
        <header class="flex-shrink-0 h-16 flex items-center justify-between px-4 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shadow-sm z-10">
            <div class="flex items-center gap-3">
                <!-- Mobile Hamburger -->
                <button @click="mobileSidebarOpen = true" class="md:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <!-- Desktop sidebar toggle -->
                <button @click="sidebarOpen = !sidebarOpen" class="hidden md:block text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">
                    <i class="bi bi-layout-sidebar text-xl"></i>
                </button>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">@yield('page-title', 'AI Chat')</span>
            </div>

            <div class="flex items-center gap-3">
                <!-- Model Selector (visible only on chat page) -->
                @yield('navbar-extras')

                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <i class="bi" :class="darkMode ? 'bi-sun-fill text-yellow-400' : 'bi-moon-fill'"></i>
                </button>

                <!-- User Avatar -->
                <a href="{{ route('profile.index') }}" class="flex items-center gap-2 group">
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-violet-500">
                    @else
                        <div class="w-8 h-8 rounded-full bg-violet-600 flex items-center justify-center text-white text-sm font-bold ring-2 ring-violet-500">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200 hidden sm:inline">{{ auth()->user()->name }}</span>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mx-4 mt-3 px-4 py-2 bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 text-sm rounded-lg flex items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Page Content -->
        <main class="flex-grow overflow-hidden">
            @yield('content')
        </main>
    </div>
</div>

<script>
    // Sidebar search filter
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
