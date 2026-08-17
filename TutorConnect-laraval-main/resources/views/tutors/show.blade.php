@extends('layouts.app')

@section('title', $tutor->name . ' - ' . ($tutor->tutorProfile->headline ?? 'Verified Tutor'))

@section('content')
<div class="py-10 sm:py-14 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-brand-800">Home</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <a href="{{ route('tutors.index') }}" class="hover:text-brand-800">Tutors</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <span class="text-slate-900 font-bold truncate">{{ $tutor->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left 2 Cols: Main Profile, Bio, Availability & Reviews -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Main Header Card -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        <div class="relative shrink-0">
                            <img src="{{ $tutor->avatar_url }}" alt="{{ $tutor->name }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl object-cover ring-4 ring-slate-100 shadow-md">
                            @if($tutor->tutorProfile->is_verified)
                                <span class="absolute -bottom-2 -right-2 bg-accent-600 text-white px-2 py-0.5 rounded-full flex items-center gap-1 text-[11px] font-bold ring-2 ring-white shadow-sm" title="Verified by TutorConnect Admin">
                                    <i class="fa-solid fa-check"></i> Verified
                                </span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $tutor->name }}</h1>
                                <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 px-3 py-1 rounded-xl text-xs font-bold text-amber-700">
                                    <i class="fa-solid fa-star text-amber-500"></i>
                                    <span>{{ number_format($tutor->tutorProfile->rating_cache, 1) }}</span>
                                    <span class="text-slate-400">({{ $tutor->tutorProfile->reviews_count }} reviews)</span>
                                </div>
                            </div>
                            <p class="text-sm font-semibold text-brand-800 mt-1">{{ $tutor->tutorProfile->headline }}</p>
                            
                            <div class="mt-4 flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-600">
                                <span class="flex items-center gap-1.5"><i class="fa-solid fa-graduation-cap text-slate-400"></i> {{ $tutor->tutorProfile->qualification }}</span>
                                @if($tutor->tutorProfile->institution)
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-building-columns text-slate-400"></i> {{ $tutor->tutorProfile->institution }}</span>
                                @endif
                                <span class="flex items-center gap-1.5"><i class="fa-solid fa-briefcase text-slate-400"></i> {{ $tutor->tutorProfile->experience_years }} Years Experience</span>
                                <span class="flex items-center gap-1.5"><i class="fa-solid fa-location-dot text-slate-400"></i> {{ $tutor->city ?: 'Worldwide Online' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Subjects List -->
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Subjects Taught</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tutor->tutorProfile->subjects as $subj)
                                <a href="{{ route('tutors.index', ['subject' => $subj->slug]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-brand-50 text-slate-800 hover:text-brand-800 border border-slate-200/60 transition-colors">
                                    {{ $subj->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Biography & Philosophy -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-slate-900">About {{ $tutor->name }}</h3>
                    <div class="text-sm text-slate-600 leading-relaxed space-y-3 whitespace-pre-line">
                        {{ $tutor->tutorProfile->bio }}
                    </div>
                </div>

                <!-- Weekly Availability Schedule -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900">Weekly Schedule Availability</h3>
                        <span class="text-xs font-medium text-slate-400">Standard Timezone</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                        @php
                            $allDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            $availMap = $tutor->tutorProfile->availabilities->keyBy('day_of_week');
                        @endphp
                        @foreach($allDays as $d)
                            @php
                                $av = $availMap->get($d);
                                $isAvailable = $av && $av->is_available;
                            @endphp
                            <div class="p-3.5 rounded-2xl border {{ $isAvailable ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-200/60 bg-slate-50/50 opacity-60' }} text-center">
                                <span class="text-xs font-bold uppercase {{ $isAvailable ? 'text-emerald-800' : 'text-slate-500' }}">{{ ucfirst($d) }}</span>
                                @if($isAvailable)
                                    <p class="text-[11px] font-bold text-slate-900 mt-1">
                                        {{ date('g:i A', strtotime($av->start_time)) }} - {{ date('g:i A', strtotime($av->end_time)) }}
                                    </p>
                                @else
                                    <p class="text-[11px] font-medium text-slate-400 mt-1">Unavailable</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Student Reviews & Testimonials -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Student Reviews</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Verified feedback from completed sessions</p>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-extrabold text-slate-900">{{ number_format($tutor->tutorProfile->rating_cache, 1) }}</span>
                            <span class="text-xs text-slate-400">/ 5.0</span>
                        </div>
                    </div>

                    @if($reviews->isEmpty())
                        <div class="text-center py-8 text-slate-400 text-xs font-medium">
                            <i class="fa-regular fa-comment-dots text-3xl mb-2 text-slate-300"></i>
                            <p>No student reviews yet. Be the first to book and rate this tutor!</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($reviews as $rev)
                                <div class="border-b border-slate-100 pb-6 last:border-0 last:pb-0 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $rev->student->avatar_url }}" alt="{{ $rev->student->name }}" class="w-9 h-9 rounded-full object-cover">
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-900">{{ $rev->student->name }}</h4>
                                                <p class="text-[10px] text-slate-400">{{ $rev->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center text-amber-400 text-xs">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed pl-12">
                                        "{{ $rev->comment }}"
                                    </p>
                                </div>
                            @endforeach
                        </div>
                        <div class="pt-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Col: Booking Action Widget & Quick Contact -->
            <div class="space-y-6 sticky top-28">
                
                <!-- Booking Widget Card -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/50 space-y-6">
                    <div class="flex items-baseline justify-between">
                        <div>
                            <span class="text-xs text-slate-400 font-bold uppercase">Hourly Rate</span>
                            <p class="text-3xl font-extrabold text-slate-900">${{ number_format($tutor->tutorProfile->hourly_rate, 2) }}<span class="text-xs font-normal text-slate-500"> / hour</span></p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Available Now
                        </span>
                    </div>

                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between text-xs text-slate-600">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-video text-brand-800"></i> Mode</span>
                            <span class="font-bold text-slate-900">{{ ucfirst(str_replace('_', ' ', $tutor->tutorProfile->teaching_mode)) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-600">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-shield-check text-accent-600"></i> Guarantee</span>
                            <span class="font-bold text-slate-900">100% Refundable</span>
                        </div>
                    </div>

                    <div class="pt-4 space-y-3">
                        @auth
                            @if(auth()->user()->isStudent())
                                <a href="{{ route('student.bookings.create', $tutor->id) }}" class="w-full bg-accent-600 hover:bg-accent-700 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-accent-600/25 hover:shadow-xl transition-all flex items-center justify-center gap-2 text-sm">
                                    <i class="fa-regular fa-calendar-check"></i>
                                    <span>Book 1-on-1 Session</span>
                                </a>
                                <a href="{{ route('student.messages.show', $tutor->id) }}" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-3 px-6 rounded-2xl transition-all flex items-center justify-center gap-2 text-xs">
                                    <i class="fa-regular fa-comment"></i>
                                    <span>Send Direct Message</span>
                                </a>
                            @elseif(auth()->user()->isTutor())
                                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-800 text-center font-medium">
                                    You are logged in as a Tutor. Log in as a Student to book sessions.
                                </div>
                            @endif
                        @else
                            <a href="{{ route('register') }}?role=student" class="w-full bg-accent-600 hover:bg-accent-700 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-accent-600/25 hover:shadow-xl transition-all flex items-center justify-center gap-2 text-sm">
                                <i class="fa-regular fa-calendar-check"></i>
                                <span>Sign In to Book Session</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Related Tutors -->
                @if($relatedTutors->isNotEmpty())
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Similar Tutors</h4>
                        <div class="space-y-3">
                            @foreach($relatedTutors as $rel)
                                <a href="{{ route('tutors.show', $rel->user_id) }}" class="flex items-center gap-3 p-2 hover:bg-slate-50 rounded-2xl transition-colors group">
                                    <img src="{{ $rel->user->avatar_url }}" alt="{{ $rel->user->name }}" class="w-10 h-10 rounded-xl object-cover">
                                    <div class="flex-1 min-w-0">
                                        <h5 class="text-xs font-bold text-slate-900 group-hover:text-brand-800 truncate">{{ $rel->user->name }}</h5>
                                        <p class="text-[11px] text-slate-500 truncate">${{ number_format($rel->hourly_rate, 2) }}/hr</p>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand-800"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>
@endsection
