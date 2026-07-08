@extends('layouts.chat')

@section('title', isset($chat) ? $chat->title : 'New Chat')
@section('page-title', isset($chat) ? $chat->title : 'New Chat')

@section('content')
<div class="flex flex-col h-full bg-gray-50 dark:bg-gray-950">

    <!-- ── CHAT MESSAGES AREA ── -->
    <div class="flex-grow overflow-y-auto px-4 py-8 scroll-smooth" id="chatMessages">
        <div class="max-w-3xl mx-auto space-y-6">

            @if(!isset($messages) || $messages->isEmpty())
                <!-- Welcome / Empty State -->
                <div class="flex flex-col items-center justify-center min-h-[60vh] text-center" id="welcomeScreen">
                  
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">How can I help you today?</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mb-8">
                        Powered by OpenRouter. Ask anything, draft emails, or explore ideas.
                    </p>
                    
                </div>
            @else
                @foreach($messages as $message)
                    @if($message->role === 'user')
                        <!-- User Message -->
                        <div class="flex justify-end gap-3 items-end">
                            <div class="max-w-xl">
                                <div class="bg-violet-600 text-white rounded-2xl rounded-br-sm px-4 py-3 shadow-sm">
                                    <p class="text-sm whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
                                </div>
                                <p class="text-xs text-gray-400 text-right mt-1 mr-1">{{ $message->created_at->format('g:i A') }}</p>
                            </div>
                            @if(auth()->user()->profile_photo_path)
                                <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="User" class="w-9 h-9 flex-shrink-0 rounded-full object-cover shadow-sm ring-1 ring-black/5 dark:ring-white/10">
                            @else
                                <div class="w-9 h-9 flex-shrink-0 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shadow-sm ring-1 ring-black/5 dark:ring-white/10">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- AI Message -->
                        <div class="flex justify-start gap-3 items-end">
                            <div class="w-9 h-9 flex-shrink-0 rounded-full bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-700 flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"></path></svg>
                            </div>
                            <div class="max-w-3xl flex-1 w-full">
                                <div class="bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-800/60 text-gray-800 dark:text-gray-200 rounded-2xl rounded-bl-sm px-5 py-4 shadow-sm w-full">
                                    <div class="chat-bubble prose prose-sm dark:prose-invert max-w-none text-inherit leading-relaxed ai-message-content">
                                        <div class="raw-markdown" style="display: none;">{{ $message->message }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 mt-1.5 ml-1">
                                    <span class="text-xs text-gray-400">{{ $message->created_at->format('g:i A') }}</span>
                                    <button onclick="copyToClipboard(this)" data-content="{{ e($message->message) }}"
                                            class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex items-center gap-1 transition-colors">
                                        <i class="bi bi-copy"></i> Copy Response
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            <!-- Typing Indicator (hidden by default) -->
            <div class="flex justify-start gap-3 items-end hidden" id="typingIndicator">
                <div class="w-9 h-9 flex-shrink-0 rounded-full bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-700 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-violet-600 dark:text-violet-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"></path></svg>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-violet-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                        <span class="w-2 h-2 bg-violet-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                        <span class="w-2 h-2 bg-violet-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                    </div>
                </div>
            </div>

        </div><!-- /max-w -->
    </div><!-- /chatMessages -->

    <!-- ── INPUT AREA ── -->
    <div class=" bg-transparent px-4 py-3">
        <div class="max-w-3xl mx-auto">

            <!-- Input Card -->
            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus-within:border-violet-400 dark:focus-within:border-violet-600 focus-within:ring-2 focus-within:ring-violet-500/20 transition-all duration-200 shadow-sm">

                <!-- Textarea -->
                <div class="px-4 pt-3 pb-1">
                    <textarea id="messageInput" rows="1"
                              placeholder="Message ShadowAI…"
                              class="w-full resize-none bg-transparent text-sm text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none leading-relaxed max-h-48"></textarea>
                </div>

                <!-- Bottom Toolbar -->
                <div class="flex items-center justify-between px-3 py-2 border-t border-gray-100 dark:border-gray-800">

                    <!-- Left: Model selector -->
                    <div class="relative flex items-center group">
                        <div class="absolute left-2.5 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <select id="modelSelector"
                                class="pl-8 pr-7 py-1.5 text-xs font-medium bg-gray-100 dark:bg-[#1a1d24] border border-transparent hover:border-gray-200 dark:hover:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500/30 appearance-none cursor-pointer transition-all shadow-sm">
                            <option value="poolside/laguna-xs-2.1:free">Laguna XS 2.1</option>
                            <option value="cohere/north-mini-code:free">Cohere North Mini</option>
                            <option value="nvidia/nemotron-3-ultra-550b-a55b:free">Nemotron Ultra 550B</option>
                        </select>
                        <div class="absolute right-2 flex items-center pointer-events-none text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <!-- Right: Send + hint -->
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] text-gray-400 hidden sm:inline-flex items-center gap-1">
                            <kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded font-sans">Enter</kbd> to send
                        </span>
                        <button id="sendBtn" type="button"
                                class="w-8 h-8 flex items-center justify-center rounded-full bg-violet-600 hover:bg-violet-500 active:bg-violet-700 text-white transition-all duration-200 disabled:opacity-100 disabled:bg-gray-100 dark:disabled:bg-[#1a1d24] disabled:text-gray-400 dark:disabled:text-gray-600 disabled:cursor-not-allowed shadow-sm hover:shadow-md disabled:shadow-none">
                            <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <p class="text-xs text-center text-gray-400 dark:text-gray-600 mt-2">
                AI can make mistakes. Verify important information.
            </p>
        </div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let currentChatId = {{ isset($chat) ? $chat->id : 'null' }};
    const chatMessages   = document.getElementById('chatMessages');
    const messageInput   = document.getElementById('messageInput');
    const sendBtn        = document.getElementById('sendBtn');
    const typingIndicator = document.getElementById('typingIndicator');
    const welcomeScreen   = document.getElementById('welcomeScreen');
    const userInitial     = "{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}";
    const userProfileUrl  = "{{ auth()->user()->profile_photo_path ? Storage::url(auth()->user()->profile_photo_path) : '' }}";

    /* ── Configure Markdown & Highlighting ── */
    if (typeof marked !== 'undefined' && typeof hljs !== 'undefined') {
        marked.setOptions({
            highlight: function(code, lang) {
                const language = hljs.getLanguage(lang) ? lang : 'plaintext';
                return hljs.highlight(code, { language }).value;
            },
            langPrefix: 'hljs language-',
            breaks: true,
            gfm: true
        });
        
        if (typeof markedKatex !== 'undefined') {
            marked.use(markedKatex({
                throwOnError: false,
                displayMode: true
            }));
        }
    }

    // Render existing AI messages on the client side to ensure math is parsed correctly
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.ai-message-content').forEach(container => {
            const rawDiv = container.querySelector('.raw-markdown');
            if (rawDiv) {
                const content = rawDiv.textContent;
                container.innerHTML = marked.parse(content);
            }
        });
    });

    document.addEventListener('DOMContentLoaded', (event) => {
        if (typeof hljs !== 'undefined') {
            document.querySelectorAll('pre code').forEach((el) => {
                hljs.highlightElement(el);
            });
        }
    });

    /* ── Auto-resize textarea ── */
    messageInput.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 192) + 'px';
    });

    /* ── Enter / Shift+Enter ── */
    messageInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    sendBtn.addEventListener('click', sendMessage);

    /* ── Helper: model value ── */
    function getModel() {
        return document.getElementById('modelSelector').value;
    }

    /* ── Suggestion quick-fill ── */
    function fillInput(text) {
        messageInput.value = text;
        messageInput.dispatchEvent(new Event('input'));
        messageInput.focus();
    }

    /* ── Scroll to bottom ── */
    function scrollToBottom(smooth = true) {
        chatMessages.scrollTo({ top: chatMessages.scrollHeight, behavior: smooth ? 'smooth' : 'instant' });
    }

    /* ── Bubble builders ── */
    function createUserBubble(content) {
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const div = document.createElement('div');
        div.className = 'flex justify-end gap-3 items-end message-item';
        
        let avatarHtml = '';
        if (userProfileUrl) {
            avatarHtml = `<img src="${userProfileUrl}" alt="User" class="w-9 h-9 flex-shrink-0 rounded-full object-cover shadow-sm ring-1 ring-black/5 dark:ring-white/10">`;
        } else {
            avatarHtml = `<div class="w-9 h-9 flex-shrink-0 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shadow-sm ring-1 ring-black/5 dark:ring-white/10">${userInitial}</div>`;
        }

        div.innerHTML = `
            <div class="max-w-xl">
                <div class="bg-violet-600 text-white rounded-2xl rounded-br-sm px-4 py-3 shadow-sm">
                    <p class="text-sm whitespace-pre-wrap leading-relaxed">${escapeHtml(content)}</p>
                </div>
                <p class="text-xs text-gray-400 text-right mt-1 mr-1">${time}</p>
            </div>
            ${avatarHtml}`;
        return div;
    }

    function createAiBubble(content) {
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const div = document.createElement('div');
        div.className = 'flex justify-start gap-3 items-end message-item';
        div.innerHTML = `
            <div class="w-9 h-9 flex-shrink-0 rounded-full bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-700 flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"></path></svg>
            </div>
            <div class="max-w-3xl flex-1 w-full">
                <div class="bg-white dark:bg-[#1a1d24] border border-gray-200 dark:border-gray-800/60 text-gray-800 dark:text-gray-200 rounded-2xl rounded-bl-sm px-5 py-4 shadow-sm w-full">
                    <div class="chat-bubble prose prose-sm dark:prose-invert max-w-none leading-relaxed">${marked.parse(content)}</div>
                </div>
                <div class="flex items-center gap-3 mt-1.5 ml-1">
                    <span class="text-xs text-gray-400">${time}</span>
                    <button onclick="copyToClipboard(this)" data-content="${escapeHtml(content)}"
                            class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex items-center gap-1 transition-colors">
                        <i class="bi bi-copy"></i> Copy Response
                    </button>
                </div>
            </div>`;
        return div;
    }

    /* ── Main send ── */
    function sendMessage() {
        const content = messageInput.value.trim();
        if (!content || sendBtn.disabled) return;

        // Reset input
        messageInput.value = '';
        messageInput.style.height = 'auto';

        // Hide welcome screen
        if (welcomeScreen) welcomeScreen.classList.add('hidden');

        // Append user bubble before indicator
        const inner = chatMessages.querySelector('.max-w-3xl');
        inner.insertBefore(createUserBubble(content), typingIndicator);

        // Show typing
        typingIndicator.classList.remove('hidden');
        scrollToBottom();

        // Disable controls
        sendBtn.disabled      = true;
        messageInput.disabled = true;

        fetch("{{ route('chat.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: content, chat_id: currentChatId, model: getModel() }),
        })
        .then(r => r.json())
        .then(data => {
            typingIndicator.classList.add('hidden');
            if (data.success) {
                currentChatId = data.chat_id;
                window.history.replaceState({}, '', `/chat/${data.chat_id}`);
                inner.insertBefore(createAiBubble(data.message), typingIndicator);
                scrollToBottom();
            } else {
                showError(data.error || 'Something went wrong. Please try again.');
            }
        })
        .catch(() => {
            typingIndicator.classList.add('hidden');
            showError('Network error. Please check your connection.');
        })
        .finally(() => {
            sendBtn.disabled      = false;
            messageInput.disabled = false;
            messageInput.focus();
        });
    }

    /* ── Error banner ── */
    function showError(msg) {
        const inner = chatMessages.querySelector('.max-w-3xl');
        const div = document.createElement('div');
        div.className = 'flex justify-center';
        div.innerHTML = `
            <div class="inline-flex items-center gap-2 text-xs text-red-600 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl px-4 py-2">
                <i class="bi bi-exclamation-circle"></i> ${escapeHtml(msg)}
            </div>`;
        inner.insertBefore(div, typingIndicator);
        scrollToBottom();
    }

    /* ── Copy ── */
    function copyToClipboard(btn) {
        navigator.clipboard.writeText(btn.dataset.content).then(() => {
            const icon = btn.querySelector('i');
            btn.innerHTML = '<i class="bi bi-check2 text-green-500"></i> Copied!';
            setTimeout(() => { btn.innerHTML = '<i class="bi bi-copy"></i> Copy'; }, 2000);
        });
    }

    /* ── Escape HTML ── */
    function escapeHtml(text) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(text));
        return d.innerHTML;
    }

    // Scroll to bottom on page load
    scrollToBottom(false);
</script>
@endsection
