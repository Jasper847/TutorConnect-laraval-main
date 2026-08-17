@extends('layouts.dashboard')

@section('title', 'Study Materials')
@section('header', 'Study Materials & Course Resources')
@section('subheader', 'Download exclusive cheat sheets, slides, and notes provided by your tutors')

@section('content')
<div class="space-y-6">
    
    @if($materials->isEmpty())
        <div class="bg-white p-12 rounded-3xl border border-slate-200/80 text-center space-y-4 shadow-sm">
            <i class="fa-regular fa-folder-open text-4xl text-slate-300"></i>
            <h3 class="text-base font-bold text-slate-900">No study materials available yet</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Once you book sessions with tutors, learning notes and slides uploaded by your tutors will appear here.</p>
            <a href="{{ route('tutors.index') }}" class="inline-block bg-brand-800 text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-sm">
                Explore Tutors
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($materials as $mat)
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-brand-50 text-brand-800 border border-brand-200/60">
                                {{ $mat->material_type }}
                            </span>
                            <span class="text-[11px] text-slate-400 font-medium">{{ $mat->formatted_size }}</span>
                        </div>

                        <h4 class="text-sm font-bold text-slate-900 leading-snug line-clamp-2">{{ $mat->title }}</h4>
                        
                        @if($mat->description)
                            <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">{{ $mat->description }}</p>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <img src="{{ $mat->tutor->avatar_url }}" alt="{{ $mat->tutor->name }}" class="w-7 h-7 rounded-full object-cover">
                            <span class="text-xs font-semibold text-slate-700 truncate max-w-[120px]">{{ $mat->tutor->name }}</span>
                        </div>

                        <a href="{{ route('student.materials.download', $mat->id) }}" class="inline-flex items-center gap-1.5 bg-accent-600 hover:bg-accent-700 text-white text-xs font-bold px-3.5 py-2 rounded-xl shadow-sm transition-all">
                            <i class="fa-solid fa-download text-[10px]"></i>
                            <span>Download</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $materials->links() }}
        </div>
    @endif

</div>
@endsection
