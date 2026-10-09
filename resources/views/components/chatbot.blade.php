<!-- Mobile Backdrop Overlay for Chatbot -->
<div id="chatbot-backdrop" onclick="toggleChatbot()" class="hidden fixed inset-0 bg-slate-950/40 backdrop-blur-xs z-40 sm:hidden transition-opacity pointer-events-auto"></div>

<!-- Floating Chatbot Assistant Widget -->
<div id="sjut-chatbot-container" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50 pointer-events-none select-none font-sans antialiased text-slate-800 dark:text-zinc-100">

    <!-- Chatbot Launcher Button -->
    <button id="sjut-chatbot-toggle"
            onclick="toggleChatbot()"
            class="pointer-events-auto relative w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-tr from-purple-900 via-purple-700 to-indigo-600 text-white shadow-2xl hover:shadow-purple-900/50 hover:scale-105 active:scale-95 transition-all duration-300 flex items-center justify-center cursor-pointer border-2 border-amber-400/80 group"
            aria-label="Open SJUT Recovery Assistant"
            title="SJUT Recovery Assistant">
        <!-- Floating Ping Indicator -->
        <span class="absolute -top-0.5 -right-0.5 flex h-3 sm:h-3.5 w-3 sm:w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 sm:h-3.5 w-3 sm:w-3.5 bg-amber-400 border-2 border-purple-950"></span>
        </span>

        <!-- Icons (toggle between robot/chat and close) -->
        <i id="chatbot-icon-open" class="fa-solid fa-headset text-lg sm:text-2xl text-amber-300 group-hover:rotate-12 transition-transform"></i>
        <i id="chatbot-icon-close" class="fa-solid fa-xmark text-lg sm:text-xl text-white hidden"></i>
    </button>

    <!-- Chatbot Window Panel -->
    <div id="sjut-chatbot-window"
         class="hidden fixed bottom-18 sm:bottom-24 right-3 sm:right-6 w-[calc(100vw-1.5rem)] sm:w-96 max-w-[380px] sm:max-w-md h-[490px] sm:h-[540px] max-h-[75vh] sm:max-h-[80vh] bg-white dark:bg-zinc-900 border border-purple-200/80 dark:border-zinc-800 rounded-3xl shadow-2xl flex flex-col overflow-hidden animate-in fade-in slide-in-from-bottom-5 duration-200 z-50 pointer-events-auto">

        <!-- Chat Window Header -->
        <div class="px-5 py-4 bg-gradient-to-r from-purple-950 via-purple-900 to-indigo-950 text-white flex items-center justify-between border-b border-purple-800/60 shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative w-10 h-10 rounded-2xl bg-white/10 backdrop-blur-md border border-amber-400/60 flex items-center justify-center text-amber-300 text-lg shadow-inner">
                    <i class="fa-solid fa-robot"></i>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-purple-950"></span>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm text-white leading-tight flex items-center gap-1.5">
                        <span>SJUT Assistant</span>
                        <span class="px-1.5 py-0.5 rounded-full bg-amber-400/20 text-amber-300 text-[9px] font-extrabold tracking-wider uppercase border border-amber-400/30">AI Help</span>
                    </h3>
                    <p class="text-[11px] text-purple-200/80">Lost & Found Support • Online</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <button onclick="resetChatbotConversation()" class="w-8 h-8 rounded-xl text-purple-200 hover:text-white hover:bg-white/10 flex items-center justify-center transition-colors text-xs cursor-pointer" title="Reset chat history">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
                <button onclick="toggleChatbot()" class="w-8 h-8 rounded-xl text-purple-200 hover:text-white hover:bg-white/10 flex items-center justify-center transition-colors text-xs cursor-pointer" title="Minimize assistant">
                    <i class="fa-solid fa-minus"></i>
                </button>
            </div>
        </div>

        <!-- Chat Messages Container -->
        <div id="chatbot-messages" class="flex-1 p-4 overflow-y-auto space-y-3.5 bg-slate-50/50 dark:bg-zinc-950/40 text-xs">

            <!-- Initial Assistant Welcome Message -->
            <div class="flex items-start gap-2.5 max-w-[90%]">
                <div class="w-7 h-7 rounded-xl bg-purple-900 text-amber-300 flex items-center justify-center text-xs shrink-0 shadow-xs">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="space-y-2">
                    <div class="p-3.5 rounded-2xl rounded-tl-sm bg-white dark:bg-zinc-800 border border-slate-200/80 dark:border-zinc-700/80 shadow-xs text-slate-800 dark:text-zinc-200 leading-relaxed">
                        <p class="font-bold text-slate-900 dark:text-white mb-1">Hello! Welcome to SJUT Assistant 👋</p>
                        <p>I'm here to help you navigate property recovery at St. John's University of Tanzania. Ask me a question, or pick a topic below!</p>
                    </div>

                    <!-- Quick Action Shortcuts -->
                    <div class="flex flex-wrap gap-1.5 pt-1">
                        <a href="{{ route('lost-items.create') }}" class="px-3 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/30 text-[11px] font-bold flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-circle-exclamation"></i> Report Lost
                        </a>
                        <a href="{{ route('found-items.create') }}" class="px-3 py-1.5 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 text-purple-700 dark:text-purple-300 border border-purple-500/30 text-[11px] font-bold flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-hand-holding-hand"></i> Report Found
                        </a>
                        <a href="{{ route('guide.download') }}" class="px-3 py-1.5 rounded-xl bg-blue-500/10 hover:bg-blue-500/20 text-blue-700 dark:text-blue-300 border border-blue-500/30 text-[11px] font-bold flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-file-arrow-down"></i> User Guide
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Dynamic Quick Chips Carousel -->
        <div id="chatbot-chips-bar" class="px-3 py-2 bg-white dark:bg-zinc-900 border-t border-slate-100 dark:border-zinc-800 overflow-x-auto whitespace-nowrap scrollbar-none flex items-center gap-1.5 shrink-0">
            <button onclick="sendPredefinedQuery('How do I report a lost item?')" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-zinc-800 hover:bg-purple-100 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 text-[11px] font-medium transition-colors cursor-pointer shrink-0">
                🔍 Report Lost
            </button>
            <button onclick="sendPredefinedQuery('How do I report a found item?')" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-zinc-800 hover:bg-purple-100 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 text-[11px] font-medium transition-colors cursor-pointer shrink-0">
                📦 Report Found
            </button>
            <button onclick="sendPredefinedQuery('How does the claim process work?')" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-zinc-800 hover:bg-purple-100 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 text-[11px] font-medium transition-colors cursor-pointer shrink-0">
                🎫 Claim & QR Pass
            </button>
            <button onclick="sendPredefinedQuery('Where is the Security Desk?')" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-zinc-800 hover:bg-purple-100 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 text-[11px] font-medium transition-colors cursor-pointer shrink-0">
                📍 Security Desk
            </button>
            <button onclick="sendPredefinedQuery('How do I download the system guide?')" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-zinc-800 hover:bg-purple-100 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 text-[11px] font-medium transition-colors cursor-pointer shrink-0">
                📥 Download Guide
            </button>
        </div>

        <!-- Chat Input Form -->
        <form id="chatbot-form" onsubmit="handleChatbotSubmit(event)" class="p-3 bg-white dark:bg-zinc-900 border-t border-slate-200 dark:border-zinc-800 flex items-center gap-2 shrink-0">
            <input type="text"
                   id="chatbot-input"
                   placeholder="Ask a question about the system..."
                   autocomplete="off"
                   class="flex-1 px-4 py-2.5 rounded-2xl bg-slate-100 dark:bg-zinc-800 text-slate-800 dark:text-zinc-100 text-xs focus:outline-none focus:ring-2 focus:ring-purple-700 border-none placeholder-slate-400 dark:placeholder-zinc-500">
            <button type="submit"
                    id="chatbot-send-btn"
                    class="w-10 h-10 rounded-2xl bg-purple-900 hover:bg-purple-800 text-white flex items-center justify-center text-xs transition-colors shadow-sm disabled:opacity-50 cursor-pointer"
                    title="Send message">
                <i class="fa-solid fa-paper-plane text-amber-300"></i>
            </button>
        </form>

    </div>
</div>

<script>
    let isChatbotOpen = false;

    function toggleChatbot() {
        const win = document.getElementById('sjut-chatbot-window');
        const iconOpen = document.getElementById('chatbot-icon-open');
        const iconClose = document.getElementById('chatbot-icon-close');
        const input = document.getElementById('chatbot-input');
        const backdrop = document.getElementById('chatbot-backdrop');

        isChatbotOpen = !isChatbotOpen;

        if (isChatbotOpen) {
            win.classList.remove('hidden');
            if (backdrop) backdrop.classList.remove('hidden');
            iconOpen.classList.add('hidden');
            iconClose.classList.remove('hidden');
            setTimeout(() => input && input.focus(), 150);
            scrollToBottom();
        } else {
            win.classList.add('hidden');
            if (backdrop) backdrop.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isChatbotOpen) {
            toggleChatbot();
        }
    });

    function scrollToBottom() {
        const container = document.getElementById('chatbot-messages');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    function sendPredefinedQuery(text) {
        const input = document.getElementById('chatbot-input');
        input.value = text;
        handleChatbotSubmit(new Event('submit'));
    }

    function resetChatbotConversation() {
        const container = document.getElementById('chatbot-messages');
        container.innerHTML = `
            <div class="flex items-start gap-2.5 max-w-[90%]">
                <div class="w-7 h-7 rounded-xl bg-purple-900 text-amber-300 flex items-center justify-center text-xs shrink-0 shadow-xs">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="space-y-2">
                    <div class="p-3.5 rounded-2xl rounded-tl-sm bg-white dark:bg-zinc-800 border border-slate-200/80 dark:border-zinc-700/80 shadow-xs text-slate-800 dark:text-zinc-200 leading-relaxed">
                        <p class="font-bold text-slate-900 dark:text-white mb-1">Conversation restarted 👋</p>
                        <p>How can I help you today with lost or found items on the SJUT campus?</p>
                    </div>
                    <div class="flex flex-wrap gap-1.5 pt-1">
                        <a href="{{ route('lost-items.create') }}" class="px-3 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/30 text-[11px] font-bold flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-circle-exclamation"></i> Report Lost
                        </a>
                        <a href="{{ route('found-items.create') }}" class="px-3 py-1.5 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 text-purple-700 dark:text-purple-300 border border-purple-500/30 text-[11px] font-bold flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-hand-holding-hand"></i> Report Found
                        </a>
                    </div>
                </div>
            </div>
        `;
        scrollToBottom();
    }

    async function handleChatbotSubmit(e) {
        if (e) e.preventDefault();
        const input = document.getElementById('chatbot-input');
        const btn = document.getElementById('chatbot-send-btn');
        const text = input.value.trim();

        if (!text) return;

        // Append User Bubble
        appendUserMessage(text);
        input.value = '';
        btn.disabled = true;

        // Typing indicator
        const typingId = appendTypingIndicator();
        scrollToBottom();

        try {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = tokenMeta ? tokenMeta.getAttribute('content') : '{{ csrf_token() }}';

            const response = await fetch("{{ route('chatbot.query') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });

            removeTypingIndicator(typingId);

            if (response.ok) {
                const data = await response.json();
                appendAssistantMessage(data.reply, data.actions || []);
                if (data.chips && data.chips.length > 0) {
                    updateChips(data.chips);
                }
            } else {
                appendAssistantMessage("I apologize, but I encountered a temporary connection issue. Please feel free to retry or browse the System User Guide directly.", [
                    { label: 'Download User Guide', url: "{{ route('guide.download') }}", icon: 'fa-file-arrow-down' }
                ]);
            }
        } catch (err) {
            removeTypingIndicator(typingId);
            appendAssistantMessage("I'm here to assist! To report an item, use 'Report Lost Item' or 'Report Found Item' from the top menu, or visit the Campus Security Desk.", [
                { label: 'Report Lost Item', url: "{{ route('lost-items.create') }}", icon: 'fa-circle-exclamation' },
                { label: 'Report Found Item', url: "{{ route('found-items.create') }}", icon: 'fa-hand-holding-hand' }
            ]);
        } finally {
            btn.disabled = false;
            scrollToBottom();
        }
    }

    function appendUserMessage(text) {
        const container = document.getElementById('chatbot-messages');
        const div = document.createElement('div');
        div.className = 'flex items-end justify-end gap-2 max-w-[88%] ml-auto';
        div.innerHTML = `
            <div class="p-3.5 rounded-2xl rounded-tr-sm bg-purple-900 text-white shadow-xs text-xs leading-relaxed">
                ${escapeHtml(text)}
            </div>
        `;
        container.appendChild(div);
        scrollToBottom();
    }

    function appendTypingIndicator() {
        const id = 'typing-' + Date.now();
        const container = document.getElementById('chatbot-messages');
        const div = document.createElement('div');
        div.id = id;
        div.className = 'flex items-center gap-2 max-w-[80%]';
        div.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-purple-900 text-amber-300 flex items-center justify-center text-xs shrink-0">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div class="px-4 py-2.5 rounded-2xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-600 animate-bounce"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-purple-600 animate-bounce [animation-delay:0.2s]"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-purple-600 animate-bounce [animation-delay:0.4s]"></span>
            </div>
        `;
        container.appendChild(div);
        return id;
    }

    function removeTypingIndicator(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function appendAssistantMessage(text, actions = []) {
        const container = document.getElementById('chatbot-messages');
        const div = document.createElement('div');
        div.className = 'flex items-start gap-2.5 max-w-[92%]';

        let formattedText = formatMarkdown(text);

        let actionsHtml = '';
        if (actions && actions.length > 0) {
            actionsHtml = '<div class="flex flex-wrap gap-1.5 pt-2">';
            actions.forEach(action => {
                const isPrimary = action.primary;
                const bgClass = isPrimary 
                    ? 'bg-purple-900 hover:bg-purple-800 text-white font-bold' 
                    : 'bg-slate-100 dark:bg-zinc-800 hover:bg-purple-50 dark:hover:bg-zinc-700 text-slate-800 dark:text-zinc-200 border border-slate-200 dark:border-zinc-700 font-semibold';
                const iconHtml = action.icon ? `<i class="fa-solid ${action.icon} ${isPrimary ? 'text-amber-300' : 'text-purple-600 dark:text-purple-400'}"></i>` : '';
                actionsHtml += `
                    <a href="${action.url}" class="px-3 py-1.5 rounded-xl ${bgClass} text-[11px] flex items-center gap-1.5 transition-colors shadow-xs">
                        ${iconHtml} <span>${action.label}</span>
                    </a>
                `;
            });
            actionsHtml += '</div>';
        }

        div.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-purple-900 text-amber-300 flex items-center justify-center text-xs shrink-0 shadow-xs">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div class="space-y-1.5 flex-1">
                <div class="p-3.5 rounded-2xl rounded-tl-sm bg-white dark:bg-zinc-800 border border-slate-200/80 dark:border-zinc-700/80 shadow-xs text-slate-800 dark:text-zinc-200 leading-relaxed text-xs">
                    ${formattedText}
                </div>
                ${actionsHtml}
            </div>
        `;
        container.appendChild(div);
        scrollToBottom();
    }

    function updateChips(chips) {
        const chipsBar = document.getElementById('chatbot-chips-bar');
        if (!chipsBar) return;
        let html = '';
        chips.forEach(chip => {
            html += `
                <button onclick="sendPredefinedQuery('${escapeQuotes(chip)}')" class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-zinc-800 hover:bg-purple-100 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 text-[11px] font-medium transition-colors cursor-pointer shrink-0">
                    ${escapeHtml(chip)}
                </button>
            `;
        });
        chipsBar.innerHTML = html;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }

    function escapeQuotes(text) {
        return text.replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    function formatMarkdown(text) {
        let html = escapeHtml(text);
        // Bold: **text**
        html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Bullets
        html = html.replace(/\n• /g, '<br>• ');
        // Numbers
        html = html.replace(/\n(\d+\.) /g, '<br><strong>$1</strong> ');
        // Newlines
        html = html.replace(/\n\n/g, '<div class="h-2"></div>');
        html = html.replace(/\n/g, '<br>');
        return html;
    }
</script>
