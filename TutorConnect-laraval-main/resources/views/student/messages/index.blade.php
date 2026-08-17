@extends('layouts.dashboard')

@section('title', 'Student Messages')
@section('header', 'Direct Messages')
@section('subheader', 'Chat directly with your tutors regarding your sessions and coursework')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Conversations</h3>
            <span class="text-xs text-slate-400 font-medium">{{ $tutors->count() }} active chat threads</span>
        </div>

        @if($tutors->isEmpty())
            <div class="p-12 text-center space-y-3 text-slate-400">
                <i class="fa-regular fa-comments text-4xl text-slate-300"></i>
                <h4 class="text-base font-bold text-slate-900">No message conversations yet</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">When you book a session or reach out to a tutor, your conversations will appear here.</p>
                <a href="{{ route('tutors.index') }}" class="inline-block bg-brand-800 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm">
                    Browse Tutors
                </a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($tutors as $tutor)
                    <a href="{{ route('student.messages.show', $tutor->id) }}" class="p-5 flex items-center justify-between hover:bg-slate-50 transition-colors group">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="relative shrink-0">
                                <img src="{{ $tutor->avatar_url }}" alt="{{ $tutor->name }}" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-slate-100">
                                @if($tutor->unread_count > 0)
                                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-accent-600 text-white rounded-full flex items-center justify-center text-[10px] font-bold">
                                        {{ $tutor->unread_count }}
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 group-hover:text-brand-800 transition-colors truncate">
                                    {{ $tutor->name }}
                                </h4>
                                <p class="text-xs text-slate-500 truncate mt-0.5">
                                    {{ $tutor->last_message ? $tutor->last_message->message : 'Start conversation...' }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <span class="text-[11px] text-slate-400 block">
                                {{ $tutor->last_message ? $tutor->last_message->created_at->diffForHumans() : '' }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-brand-800 mt-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                Open Chat <i class="fa-solid fa-chevron-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
