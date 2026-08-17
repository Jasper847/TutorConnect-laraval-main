@extends('layouts.dashboard')

@section('title', 'Tutor Messages')
@section('header', 'Student Messages')
@section('subheader', 'Direct messages from your current and prospective students')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Student Chats</h3>
            <span class="text-xs text-slate-400 font-medium">{{ $students->count() }} conversation threads</span>
        </div>

        @if($students->isEmpty())
            <div class="p-12 text-center space-y-3 text-slate-400">
                <i class="fa-regular fa-comments text-4xl text-slate-300"></i>
                <h4 class="text-base font-bold text-slate-900">No student messages yet</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">When students send inquiries or book your classes, their messages will appear here.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($students as $student)
                    <a href="{{ route('tutor.messages.show', $student->id) }}" class="p-5 flex items-center justify-between hover:bg-slate-50 transition-colors group">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="relative shrink-0">
                                <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-slate-100">
                                @if($student->unread_count > 0)
                                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-accent-600 text-white rounded-full flex items-center justify-center text-[10px] font-bold">
                                        {{ $student->unread_count }}
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 group-hover:text-brand-800 transition-colors truncate">
                                    {{ $student->name }}
                                </h4>
                                <p class="text-xs text-slate-500 truncate mt-0.5">
                                    {{ $student->last_message ? $student->last_message->message : 'No messages yet...' }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <span class="text-[11px] text-slate-400 block">
                                {{ $student->last_message ? $student->last_message->created_at->diffForHumans() : '' }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-brand-800 mt-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                Reply <i class="fa-solid fa-chevron-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
