@extends('layouts.app')

@section('title', 'Direct Messages & Communication')

@section('content')
<div class="h-[calc(100vh-100px)] min-h-[500px] flex flex-col">

    <!-- Dual-Pane Layout -->
    <div class="flex-1 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden flex flex-col md:flex-row min-h-0">

        <!-- LEFT COLUMN: Sidebar Chat List -->
        <div class="w-full md:w-80 lg:w-96 border-r border-slate-200 dark:border-zinc-800 flex flex-col shrink-0 bg-slate-50/50 dark:bg-zinc-950/20 {{ $activeUser ? 'hidden md:flex' : 'flex' }}">

            <!-- Sidebar Header -->
            <div class="p-4 bg-white dark:bg-zinc-900 border-b border-slate-200 dark:border-zinc-800 flex items-center justify-between shrink-0">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-comments text-blue-900 dark:text-blue-400"></i> Conversations
                    </h2>
                    <span class="text-[10px] text-slate-400 block mt-0.5">SJUT Chat Center</span>
                </div>
                <button onclick="openNewChatModal()" class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-zinc-800 text-blue-900 dark:text-blue-400 hover:bg-blue-900 hover:text-white flex items-center justify-center transition-colors shadow-xs cursor-pointer" title="Start New Conversation">
                    <i class="fa-solid fa-edit text-xs"></i>
                </button>
            </div>

            <!-- Search Field -->
            <div class="p-3 bg-white dark:bg-zinc-900 border-b border-slate-200 dark:border-zinc-800 shrink-0">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="conversation-search" onkeyup="filterConversations()" placeholder="Search active chats..." class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-lg text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-900">
                </div>
            </div>

            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto p-2 space-y-1" id="conversations-list">
                @forelse($conversations as $conv)
                    <a href="{{ route('messages.index', $conv->id) }}" class="p-3 flex items-center gap-3.5 rounded-xl transition-all duration-200 conversation-item {{ $activeUser && $activeUser->id == $conv->id ? 'bg-blue-900 text-white shadow-md' : 'hover:bg-slate-100 dark:hover:bg-zinc-800/60' }}" data-name="{{ strtolower($conv->name) }}">
                        <div class="relative shrink-0">
                            <div class="w-10 h-10 rounded-full font-bold text-xs flex items-center justify-center uppercase shadow-sm border-2 border-white dark:border-zinc-900 {{ $activeUser && $activeUser->id == $conv->id ? 'bg-white text-blue-900' : 'bg-blue-900 text-white' }}">
                                {{ substr($conv->name, 0, 2) }}
                            </div>
                            @if($conv->unread_count > 0)
                                <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center border-2 border-white dark:border-zinc-900 animate-pulse">
                                    {{ $conv->unread_count }}
                                </span>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <h4 class="text-xs font-bold truncate {{ $activeUser && $activeUser->id == $conv->id ? 'text-white' : 'text-slate-900 dark:text-white' }}">{{ $conv->name }}</h4>
                                <span class="text-[9px] shrink-0 font-medium {{ $activeUser && $activeUser->id == $conv->id ? 'text-blue-200' : 'text-slate-400' }}">{{ $conv->last_message_time }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-1.5">
                                <p class="text-[11px] truncate leading-tight {{ $activeUser && $activeUser->id == $conv->id ? 'text-blue-100' : ($conv->unread_count > 0 ? 'font-bold text-slate-900 dark:text-white' : 'text-slate-500 dark:text-zinc-400') }}">
                                    {{ \Illuminate\Support\Str::limit(preg_replace('/\s+/', ' ', $conv->last_message ?: 'Start conversation...'), 28) }}
                                </p>
                                <span class="text-[8px] uppercase tracking-wider px-1.5 py-0.5 rounded font-extrabold shrink-0 {{ $activeUser && $activeUser->id == $conv->id ? 'bg-blue-800 text-white border border-blue-700/50' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400' }}">
                                    {{ $conv->role }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-slate-400 dark:text-zinc-500">
                        <i class="fa-solid fa-comments text-2xl mb-2 block opacity-40"></i>
                        <p class="text-xs font-medium">No conversation history yet.</p>
                        <button onclick="openNewChatModal()" class="mt-3 text-xs text-blue-900 font-bold hover:underline">
                            Start a new chat
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT COLUMN: Active Chat Panel -->
        <div class="flex-1 flex flex-col bg-slate-50/50 dark:bg-zinc-950/40 min-w-0 {{ !$activeUser ? 'hidden md:flex' : 'flex' }}">
            @if($activeUser)
                <!-- Chat Recipient Details Header -->
                <div class="p-3 bg-white dark:bg-zinc-900 border-b border-slate-200 dark:border-zinc-800 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <a href="{{ route('messages.index') }}" class="md:hidden text-slate-400 hover:text-slate-600 text-sm">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                        <div class="w-9 h-9 rounded-lg bg-blue-900 text-white font-bold text-xs flex items-center justify-center uppercase shrink-0 shadow-sm">
                            {{ substr($activeUser->name, 0, 2) }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <h3 class="text-xs font-bold text-slate-900 dark:text-white truncate">
                                    {{ $activeUser->name }}
                                </h3>
                                <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase shrink-0 bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300">
                                    {{ $activeUser->role }}
                                </span>
                            </div>
                            <span class="text-[10px] text-slate-400 block truncate">{{ $activeUser->email }} {{ $activeUser->registration_number ? '• Reg: '.$activeUser->registration_number : '' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-[9px] text-emerald-600 font-semibold flex items-center gap-1 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">
                            <i class="fa-solid fa-circle text-[5px] animate-pulse"></i> Connected
                        </span>
                    </div>
                </div>

                <!-- REPORT CONTEXT HEADER BANNER -->
                @if($reportContext)
                    <div class="bg-blue-900/10 border-b border-blue-900/20 p-3 flex items-center justify-between gap-4 shrink-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-blue-900 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                <i class="fa-solid fa-link"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-extrabold uppercase text-blue-600 dark:text-blue-400 block">Report Context</span>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $reportContext->item_name }}</h4>
                                <span class="text-[10px] text-slate-500 truncate block">Category: {{ $reportContext->category->category_name ?? 'General' }} | Status: {{ $reportContext->status }}</span>
                            </div>
                        </div>
                        <a href="{{ $reportType === 'found_item' ? route('found-items.show', $reportContext->id) : route('lost-items.show', $reportContext->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-900 text-white font-bold text-xs hover:bg-blue-800 transition-colors shrink-0">
                            View Report Details &rarr;
                        </a>
                    </div>
                @endif

                <!-- Scrollable Messages Window -->
                <div class="flex-1 p-4 md:p-6 overflow-y-auto space-y-4" id="messages-container">
                    @forelse($messages as $msg)
                        @if($msg->sender_id == Auth::id())
                            <div class="flex flex-col items-end max-w-[80%] sm:max-w-[70%] ml-auto message-item" data-id="{{ $msg->id }}">
                                <div class="bg-blue-900 text-white rounded-2xl rounded-tr-none px-4 py-2.5 shadow-xs text-xs leading-relaxed">
                                    <p class="whitespace-pre-line">{{ $msg->message }}</p>
                                </div>
                                <div class="flex items-center gap-1 text-[9px] text-slate-400 mt-1 px-1 font-medium">
                                    <span>{{ $msg->created_at->format('h:i A') }}</span>
                                    <i class="fa-solid {{ $msg->is_read ? 'fa-check-double text-blue-500' : 'fa-check' }}"></i>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start gap-2.5 max-w-[80%] sm:max-w-[70%] message-item" data-id="{{ $msg->id }}">
                                <div class="w-8 h-8 rounded-lg bg-slate-700 text-white font-bold text-xs flex items-center justify-center uppercase shrink-0 mt-0.5">
                                    {{ substr($activeUser->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="bg-white dark:bg-zinc-800 text-slate-900 dark:text-white border border-slate-200 dark:border-zinc-700 rounded-2xl rounded-tl-none px-4 py-2.5 shadow-xs text-xs leading-relaxed">
                                        <p class="whitespace-pre-line">{{ $msg->message }}</p>
                                    </div>
                                    <span class="text-[9px] text-slate-400 mt-1 block px-1 font-medium">{{ $msg->created_at->format('h:i A') }}</span>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-center text-slate-400 dark:text-zinc-500 p-6">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-zinc-800 text-blue-900 dark:text-blue-400 flex items-center justify-center text-xl mb-2">
                                <i class="fa-solid fa-paper-plane"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-800 dark:text-white">Start the conversation</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Send a message to {{ $activeUser->name }} regarding property claims or inquiries.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input Footer -->
                <div class="p-3 bg-white dark:bg-zinc-900 border-t border-slate-200 dark:border-zinc-800 shrink-0">
                    <form id="send-message-form" action="{{ route('messages.store') }}" method="POST" onsubmit="handleFormSubmit(event)" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" id="receiver_id" name="receiver_id" value="{{ $activeUser->id }}">
                        <input type="hidden" name="report_type" value="{{ $reportType }}">
                        <input type="hidden" name="report_id" value="{{ $reportId }}">
                        
                        <input type="text" id="message-text" name="message" required placeholder="Write a message to {{ $activeUser->name }}..." onkeydown="handleKeyDown(event)" autocomplete="off" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-lg text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-900">

                        <button type="submit" id="send-btn" class="px-4 py-2 rounded-lg bg-blue-900 hover:bg-blue-800 text-white font-bold text-xs shadow-sm transition-all flex items-center gap-1.5 shrink-0 cursor-pointer">
                            <span>Send</span> <i class="fa-solid fa-paper-plane text-[9px]"></i>
                        </button>
                    </form>
                </div>

            @else
                <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-400 dark:text-zinc-500">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-zinc-800 text-blue-900 dark:text-blue-400 flex items-center justify-center text-2xl mb-3 shadow-xs">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base mb-1">Select a Conversation</h3>
                    <p class="text-xs max-w-sm text-slate-500 dark:text-zinc-400 mb-4">Choose an active contact from the sidebar or click start a new messaging thread.</p>
                    <button onclick="openNewChatModal()" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-md transition-all flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-[10px]"></i> Start New Message
                    </button>
                </div>
            @endif
        </div>

    </div>
</div>

<!-- START NEW CHAT MODAL -->
<div id="new-chat-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl w-full max-w-md shadow-2xl p-6 relative">
        <button onclick="closeNewChatModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-blue-900 text-white flex items-center justify-center text-base shadow-sm">
                <i class="fa-solid fa-paper-plane"></i>
            </div>
            <div>
                <h3 class="font-bold text-base text-slate-900 dark:text-white">Start New Conversation</h3>
                <p class="text-xs text-slate-400">Select any registered student, staff, or admin user.</p>
            </div>
        </div>

        <div class="mb-3 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="user-lookup-search" onkeyup="filterUserLookup()" placeholder="Search user by name, email or reg number..." class="w-full pl-9 pr-3.5 py-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-900">
        </div>

        <div class="max-h-64 overflow-y-auto divide-y divide-slate-100 dark:divide-zinc-800 mb-2" id="user-lookup-list">
            @foreach($allUsers as $u)
                <a href="{{ route('messages.index', $u->id) }}" class="p-3 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-zinc-800 rounded-xl transition-colors user-lookup-item" data-name="{{ strtolower($u->name . ' ' . $u->email . ' ' . $u->registration_number) }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-blue-900 text-white font-bold text-xs flex items-center justify-center uppercase shrink-0">
                            {{ substr($u->name, 0, 2) }}
                        </div>
                        <div class="min-w-0">
                            <span class="font-bold text-xs text-slate-900 dark:text-white block truncate">{{ $u->name }}</span>
                            <span class="text-[10px] text-slate-400 block truncate">{{ $u->email }} {{ $u->registration_number ? '• Reg: '.$u->registration_number : '' }}</span>
                        </div>
                    </div>
                    <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded shrink-0 {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600' }}">
                        {{ $u->role }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    const activeUserId = "{{ $activeUser ? $activeUser->id : '' }}";
    const activeUserName = "{{ $activeUser ? addslashes($activeUser->name) : '' }}";

    document.addEventListener('DOMContentLoaded', function() {
        scrollToBottom();

        const msgInput = document.getElementById('message-text');
        if (msgInput) {
            msgInput.focus();
        }

        if (activeUserId) {
            setInterval(fetchLatestMessages, 3000);
        }
    });

    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    function handleKeyDown(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('send-message-form').requestSubmit();
        }
    }

    function handleFormSubmit(e) {
        e.preventDefault();

        const messageInput = document.getElementById('message-text');
        const receiverId = document.getElementById('receiver_id').value;
        const messageText = messageInput.value.trim();

        if (!messageText) return;

        const sendBtn = document.getElementById('send-btn');
        sendBtn.disabled = true;

        fetch("{{ route('messages.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                receiver_id: receiverId,
                message: messageText
            })
        })
        .then(response => response.json())
        .then(data => {
            sendBtn.disabled = false;
            if (data.success) {
                messageInput.value = '';
                appendMessageToContainer(data.message);
                scrollToBottom();
                messageInput.focus();
            } else if (data.error) {
                alert(data.error);
            } else {
                document.getElementById('send-message-form').submit();
            }
        })
        .catch(err => {
            sendBtn.disabled = false;
            console.error('AJAX Send error:', err);
            document.getElementById('send-message-form').submit();
        });
    }

    function fetchLatestMessages() {
        if (!activeUserId) return;

        fetch("/messages/" + activeUserId + "/fetch", {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.messages) {
                const container = document.getElementById('messages-container');
                if (!container) return;

                const currentCount = container.querySelectorAll('.message-item').length;
                if (data.messages.length !== currentCount) {
                    container.innerHTML = '';
                    data.messages.forEach(msg => {
                        appendMessageToContainer(msg);
                    });
                    scrollToBottom();
                }
            }
        })
        .catch(err => console.error(err));
    }

    function appendMessageToContainer(msg) {
        const container = document.getElementById('messages-container');
        if (!container) return;

        const isMine = msg.is_mine;
        const div = document.createElement('div');
        div.setAttribute('data-id', msg.id);

        if (isMine) {
            div.className = 'flex flex-col items-end max-w-[80%] sm:max-w-[70%] ml-auto message-item';
            div.innerHTML = `
                <div class="bg-blue-900 text-white rounded-2xl rounded-tr-none px-4 py-2.5 shadow-xs text-xs leading-relaxed">
                    <p class="whitespace-pre-line">${escapeHtml(msg.message)}</p>
                </div>
                <div class="flex items-center gap-1 text-[9px] text-slate-400 mt-1 px-1 font-medium">
                    <span>${msg.created_at}</span>
                    <i class="fa-solid ${msg.is_read ? 'fa-check-double text-blue-500' : 'fa-check'}"></i>
                </div>
            `;
        } else {
            div.className = 'flex items-start gap-2.5 max-w-[80%] sm:max-w-[70%] message-item';
            div.innerHTML = `
                <div class="w-8 h-8 rounded-lg bg-slate-700 text-white font-bold text-xs flex items-center justify-center uppercase shrink-0 mt-0.5">
                    ${escapeHtml(activeUserName.substring(0, 2))}
                </div>
                <div>
                    <div class="bg-white dark:bg-zinc-800 text-slate-900 dark:text-white border border-slate-200 dark:border-zinc-700 rounded-2xl rounded-tl-none px-4 py-2.5 shadow-xs text-xs leading-relaxed">
                        <p class="whitespace-pre-line">${escapeHtml(msg.message)}</p>
                    </div>
                    <span class="text-[9px] text-slate-400 mt-1 block px-1 font-medium">${msg.created_at}</span>
                </div>
            `;
        }

        container.appendChild(div);
    }

    function escapeHtml(str) {
        return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    function filterConversations() {
        const q = document.getElementById('conversation-search').value.toLowerCase();
        document.querySelectorAll('.conversation-item').forEach(item => {
            const name = item.getAttribute('data-name');
            item.style.display = name.includes(q) ? 'flex' : 'none';
        });
    }

    function filterUserLookup() {
        const q = document.getElementById('user-lookup-search').value.toLowerCase();
        document.querySelectorAll('.user-lookup-item').forEach(item => {
            const name = item.getAttribute('data-name');
            item.style.display = name.includes(q) ? 'flex' : 'none';
        });
    }

    function openNewChatModal() {
        document.getElementById('new-chat-modal').classList.remove('hidden');
    }

    function closeNewChatModal() {
        document.getElementById('new-chat-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection
