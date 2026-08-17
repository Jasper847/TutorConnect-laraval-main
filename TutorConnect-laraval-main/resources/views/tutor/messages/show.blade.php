@extends('layouts.dashboard')

@section('title', 'Chat with ' . $student->name)
@section('header', 'Student Conversation: ' . $student->name)
@section('subheader', 'Reply to questions and share meeting links or instructions')

@section('content')
<div class="max-w-4xl" x-data="{
    messageText: '',
    messages: {{ $messages->toJson() }},
    sending: false,
    scrollToBottom() {
        this.$nextTick(() => {
            const container = document.getElementById('chat-scroll-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    },
    init() {
        this.scrollToBottom();
        setInterval(() => {
            fetch('{{ route('tutor.messages.show', $student->id) }}', {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.messages && data.messages.length !== this.messages.length) {
                    this.messages = data.messages;
                    this.scrollToBottom();
                }
            })
            .catch(() => {});
        }, 4000);
    },
    sendMessage() {
        if (!this.messageText.trim() || this.sending) return;
        this.sending = true;
        const text = this.messageText;
        this.messageText = '';

        fetch('{{ route('tutor.messages.send', $student->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            this.sending = false;
            if (data.message) {
                this.messages.push(data.message);
                this.scrollToBottom();
            }
        })
        .catch(() => {
            this.sending = false;
        });
    }
}">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col h-[650px]">
        
        <!-- Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-3.5">
                <a href="{{ route('tutor.messages.index') }}" class="p-2 text-slate-400 hover:text-slate-700">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="w-10 h-10 rounded-2xl object-cover ring-2 ring-slate-200">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 leading-tight">{{ $student->name }}</h3>
                    <p class="text-[11px] text-slate-500 font-medium">
                        {{ $student->studentProfile->grade_level ?: 'Student' }} — {{ $student->city ?: 'Location not set' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Chat Messages -->
        <div id="chat-scroll-container" class="flex-1 p-6 overflow-y-auto space-y-4 bg-slate-50/30">
            <template x-if="messages.length === 0">
                <div class="text-center py-16 text-slate-400 space-y-2">
                    <i class="fa-regular fa-comment-dots text-3xl text-slate-300"></i>
                    <p class="text-xs">Send a message or meeting link to {{ $student->name }}.</p>
                </div>
            </template>

            <template x-for="msg in messages" :key="msg.id">
                <div class="flex flex-col" :class="msg.sender_id === {{ auth()->id() }} ? 'items-end' : 'items-start'">
                    <div class="max-w-[75%] px-4 py-3 rounded-2xl text-xs font-medium leading-relaxed shadow-sm"
                         :class="msg.sender_id === {{ auth()->id() }} 
                            ? 'bg-brand-800 text-white rounded-br-none' 
                            : 'bg-white text-slate-800 border border-slate-200/80 rounded-bl-none'">
                        <p x-text="msg.message"></p>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 px-1" x-text="new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                </div>
            </template>
        </div>

        <!-- Message Input Bar -->
        <div class="p-4 border-t border-slate-100 bg-white">
            <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                <input type="text" x-model="messageText" placeholder="Reply to student..." required
                       class="flex-1 text-xs font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
                <button type="submit" :disabled="sending || !messageText.trim()"
                        class="bg-brand-800 hover:bg-brand-900 disabled:opacity-50 text-white font-bold px-5 py-3 rounded-2xl shadow-md transition-all flex items-center gap-2 text-xs shrink-0">
                    <span>Send Reply</span>
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
