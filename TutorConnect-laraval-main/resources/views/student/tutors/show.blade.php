@extends('layouts.app')

@section('title', $tutor->name . ' — Tutor Profile')
@section('header', 'Tutor Details')
@section('subheader', 'Review academic qualifications, weekly teaching schedule, and student testimonials')

@section('content')
@php
    $profile = $tutor->tutorProfile;
    $subjects = is_array($profile->subjects) ? $profile->subjects : ($profile->subjects ? json_decode($profile->subjects, true) : []);
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $slotsKeyed = $availableSlots->keyBy(fn($s) => strtolower($s->day_of_week));
@endphp

<div class="space-y-8 max-w-5xl">
    
    <!-- Top Hero Profile Card -->
    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-start justify-between gap-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <div class="relative">
                <img src="{{ $tutor->avatar_url }}" alt="{{ $tutor->name }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover ring-4 ring-gray-100 shadow-sm">
                @if($profile->is_verified)
                    <span class="absolute -bottom-2 -right-2 bg-emerald-600 text-white w-7 h-7 rounded-full flex items-center justify-center text-xs ring-4 ring-white shadow-sm" title="Verified by TutorConnect Admin">
                        <i class="fa-solid fa-check"></i>
                    </span>
                @endif
            </div>

            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="text-2xl font-extrabold font-heading text-slate-900">{{ $tutor->name }}</h2>
                    @if($profile->is_verified)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Verified Educator
                        </span>
                    @endif
                </div>

                <p class="text-sm font-semibold text-primary-800">{{ $profile->headline }}</p>

                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 pt-1">
                    <div class="flex items-center text-amber-400">
                        <i class="fa-solid fa-star"></i>
                        <span class="ml-1.5 font-bold text-slate-900">{{ number_format($profile->avg_rating ?? ($profile->rating_cache ?? 5.0), 1) }}</span>
                        <span class="ml-1 text-slate-400">({{ $profile->reviews_count ?? $reviews->total() }} reviews)</span>
                    </div>
                    <span><i class="fa-solid fa-briefcase text-slate-400 mr-1"></i> {{ $profile->experience_years }} Years Teaching</span>
                    <span><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $profile->location ?: ($tutor->city ?: 'Worldwide Online') }}</span>
                </div>
            </div>
        </div>

        <!-- Pricing Box & Book CTA -->
        <div class="w-full md:w-auto bg-slate-50 p-5 rounded-xl border border-gray-100 flex flex-col items-center justify-center space-y-3 min-w-[200px]">
            <span class="text-[10px] uppercase font-bold text-slate-400">Hourly Rate</span>
            <div class="text-2xl font-extrabold font-heading text-slate-900">
                PKR {{ number_format($profile->hourly_rate, 0) }}<span class="text-xs font-normal text-slate-400">/hr</span>
            </div>
            <a href="{{ route('student.bookings.create', $tutor->id) }}" 
               class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-3 rounded-xl shadow-md shadow-emerald-600/20 hover:shadow-lg transition-all text-center flex items-center justify-center gap-2">
                <i class="fa-regular fa-calendar-check"></i>
                <span>Book This Tutor</span>
            </a>
            <a href="{{ route('student.messages.show', $tutor->id) }}" class="text-xs font-semibold text-primary-800 hover:underline">
                <i class="fa-regular fa-comment mr-1"></i> Send a Message
            </a>
        </div>
    </div>

    <!-- Details Grid: Bio, Subjects, Education & Availability -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Bio, Subjects & Education -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Biography -->
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-3">
                <h3 class="text-base font-bold font-heading text-slate-900">About the Tutor</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $profile->bio }}</p>
            </div>

            <!-- Subjects Taught -->
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-base font-bold font-heading text-slate-900">Academic Subjects Taught</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($subjects as $subj)
                        <span class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-primary-50 text-primary-800 border border-primary-100">
                            {{ is_array($subj) ? ($subj['name'] ?? '') : $subj }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Education & Qualifications -->
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-3">
                <h3 class="text-base font-bold font-heading text-slate-900">Education & Degrees</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $profile->education }}</p>
            </div>

            <!-- Student Reviews List (Paginated) -->
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <h3 class="text-base font-bold font-heading text-slate-900">Student Reviews ({{ $reviews->total() }})</h3>
                    <div class="flex items-center text-amber-400 text-xs">
                        <i class="fa-solid fa-star"></i>
                        <span class="ml-1 font-bold text-slate-900">{{ number_format($profile->avg_rating ?? ($profile->rating_cache ?? 5.0), 1) }} / 5.0</span>
                    </div>
                </div>

                @if($reviews->isEmpty())
                    <p class="text-xs text-slate-400 py-6 text-center">No student reviews written yet.</p>
                @else
                    <div class="space-y-6 divide-y divide-gray-100">
                        @foreach($reviews as $rev)
                            <div class="pt-6 first:pt-0 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $rev->student->avatar_url }}" alt="{{ $rev->student->name }}" class="w-9 h-9 rounded-xl object-cover">
                                        <div>
                                            <h4 class="text-xs font-bold font-heading text-slate-900">{{ $rev->student->name }}</h4>
                                            <span class="text-[10px] text-slate-400">{{ $rev->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex text-amber-400 text-xs">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed italic">"{{ $rev->comment }}"</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- Right Col: Weekly Availability Schedule Matrix -->
        <div class="space-y-6">
            
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Weekly Availability</h3>
                    <span class="text-[10px] font-semibold text-emerald-600">Active Slots</span>
                </div>

                <div class="space-y-2">
                    @foreach($days as $day)
                        @php
                            $dKey = strtolower($day);
                            $slot = $slotsKeyed->get($dKey);
                        @endphp
                        <div class="p-3 rounded-xl border {{ $slot ? 'border-emerald-100 bg-emerald-50/40' : 'border-gray-100 bg-slate-50/60 opacity-60' }} flex items-center justify-between text-xs">
                            <span class="font-bold font-heading text-slate-800">{{ $day }}</span>
                            @if($slot)
                                <span class="font-semibold text-emerald-700">
                                    {{ date('g:i A', strtotime($slot->start_time)) }} - {{ date('g:i A', strtotime($slot->end_time)) }}
                                </span>
                            @else
                                <span class="text-slate-400 font-medium">Off</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="pt-3">
                    <a href="{{ route('student.bookings.create', $tutor->id) }}" 
                       class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-3 rounded-xl shadow-md transition-all text-center block">
                        Schedule a Session
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
