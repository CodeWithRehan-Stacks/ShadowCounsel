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
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-violet-600 to-purple-700 flex items-center justify-center mb-5 shadow-lg shadow-violet-500/30">
                        <i class="bi bi-stars text-white text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">How can I help you today?</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mb-8">
                        Powered by OpenRouter. Ask anything, debug code, draft emails, or explore ideas.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full max-w-lg text-left">
                        @foreach([
                            ['icon' => 'bi-code-slash', 'text' => 'Write a Python web scraper'],
                            ['icon' => 'bi-lightbulb', 'text' => 'Explain quantum computing simply'],
                            ['icon' => 'bi-pencil-square', 'text' => 'Draft a professional email'],
                            ['icon' => 'bi-bar-chart-line', 'text' => 'Tips to improve my productivity'],
                        ] as $s)
                            <button onclick="fillInput('{{ $s['text'] }}')"
                                    class="group flex items-start gap-3 px-4 py-3.5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-violet-400 dark:hover:border-violet-600 hover:shadow-sm hover:shadow-violet-100 dark:hover:shadow-none transition-all duration-200 text-left">
                                <i class="bi {{ $s['icon'] }} text-violet-500 text-lg mt-0.5 flex-shrink-0"></i>
                                <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-violet-700 dark:group-hover:text-violet-300 font-medium transition-colors">{{ $s['text'] }}</span>
                            </button>
                        @endforeach
                    </div>
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
                            <div class="w-8 h-8 flex-shrink-0 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shadow">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                    @else
                        <!-- AI Message -->
                        <div class="flex justify-start gap-3 items-end">
                            <div class="w-8 h-8 flex-shrink-0 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 dark:from-gray-600 dark:to-gray-800 flex items-center justify-center shadow">
                                <i class="bi bi-stars text-violet-400 text-sm"></i>
                            </div>
                            <div class="max-w-xl flex-1">
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                                    <div class="chat-bubble prose prose-sm dark:prose-invert max-w-none text-inherit leading-relaxed">
                                        {!! \Illuminate\Support\Str::markdown($message->message) !!}
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 mt-1 ml-1">
                                    <span class="text-xs text-gray-400">{{ $message->created_at->format('g:i A') }}</span>
                                    <button onclick="copyToClipboard(this)" data-content="{{ e($message->message) }}"
                                            class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex items-center gap-1 transition-colors">
                                        <i class="bi bi-copy"></i> Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            <!-- Typing Indicator (hidden by default) -->
            <div class="flex justify-start gap-3 items-end hidden" id="typingIndicator">
                <div class="w-8 h-8 flex-shrink-0 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center shadow">
                    <i class="bi bi-stars text-violet-400 text-sm"></i>
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
                <div class="flex items-center justify-between px-3 py-2 border-t border-gray-200 dark:border-gray-700">

                    <!-- Left: Model selector -->
                    <div class="flex items-center gap-2">
                        <i class="bi bi-cpu text-violet-500 text-sm flex-shrink-0"></i>
                        <select id="modelSelector"
                                class="text-xs bg-transparent text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-0 border-none pr-6 appearance-none cursor-pointer hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                            <option value="poolside/laguna-xs-2.1:free">Laguna XS 2.1</option>
                            <option value="cohere/north-mini-code:free">Cohere North Mini</option>
                            <option value="nvidia/llama-nemotron-rerank-vl-1b-v2:free">Nemotron Rerank VL</option>
                            <option value="nvidia/nemotron-3.5-content-safety:free">Nemotron Safety</option>
                            <option value="nvidia/nemotron-3-ultra-550b-a55b:free">Nemotron Ultra 550B</option>
                        </select>
                    </div>

                    <!-- Right: Send + hint -->
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-400 hidden sm:inline">
                            <kbd class="px-1 py-0.5 bg-gray-200 dark:bg-gray-700 rounded text-xs">Enter</kbd> to send
                            &nbsp;·&nbsp;
                            <kbd class="px-1 py-0.5 bg-gray-200 dark:bg-gray-700 rounded text-xs">Shift+Enter</kbd> for new line
                        </span>
                        <button id="sendBtn" type="button"
                                class="w-8 h-8 flex items-center justify-center rounded-xl bg-violet-600 hover:bg-violet-700 text-white transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed shadow-sm hover:shadow-md">
                            <i class="bi bi-arrow-up-short text-lg leading-none"></i>
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
        div.innerHTML = `
            <div class="max-w-xl">
                <div class="bg-violet-600 text-white rounded-2xl rounded-br-sm px-4 py-3 shadow-sm">
                    <p class="text-sm whitespace-pre-wrap leading-relaxed">${escapeHtml(content)}</p>
                </div>
                <p class="text-xs text-gray-400 text-right mt-1 mr-1">${time}</p>
            </div>
            <div class="w-8 h-8 flex-shrink-0 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shadow">
                ${userInitial}
            </div>`;
        return div;
    }

    function createAiBubble(content) {
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const div = document.createElement('div');
        div.className = 'flex justify-start gap-3 items-end message-item';
        div.innerHTML = `
            <div class="w-8 h-8 flex-shrink-0 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center shadow">
                <i class="bi bi-stars text-violet-400 text-sm"></i>
            </div>
            <div class="max-w-xl flex-1">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                    <div class="chat-bubble prose prose-sm dark:prose-invert max-w-none leading-relaxed">${marked.parse(content)}</div>
                </div>
                <div class="flex items-center gap-3 mt-1 ml-1">
                    <span class="text-xs text-gray-400">${time}</span>
                    <button onclick="copyToClipboard(this)" data-content="${escapeHtml(content)}"
                            class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex items-center gap-1 transition-colors">
                        <i class="bi bi-copy"></i> Copy
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
