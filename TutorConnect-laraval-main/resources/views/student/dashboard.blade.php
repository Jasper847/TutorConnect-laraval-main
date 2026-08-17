@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('header', 'Welcome, ' . $student->name)
@section('subheader', 'Track your upcoming 1-on-1 tutoring sessions, recommendations, and learning progress')

@section('content')
<div class="space-y-8">
    
    <!-- 3 Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <x-stat-card 
            icon="fa-solid fa-calendar-check" 
            :value="$stats['total_bookings']" 
            label="Total Bookings" 
            color="primary" 
            :subtext="$stats['completed_sessions'] . ' sessions completed'"
        />
        <x-stat-card 
            icon="fa-regular fa-clock" 
            :value="$stats['upcoming_sessions']" 
            label="Upcoming Sessions" 
            color="emerald" 
            subtext="Confirmed and scheduled classes"
        />
        <x-stat-card 
            icon="fa-solid fa-chalkboard-user" 
            :value="$stats['tutors_worked_with']" 
            label="Tutors Worked With" 
            color="purple" 
            subtext="Unique specialized educators"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Upcoming Bookings (Next 3) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">Upcoming Tutoring Sessions</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Your next scheduled 1-on-1 classes</p>
                    </div>
                    <a href="{{ route('student.bookings.index') }}" class="text-xs font-bold text-primary-800 hover:underline">
                        View All Bookings &rarr;
                    </a>
                </div>

                @if($upcomingBookings->isEmpty())
                    <div class="p-8 rounded-xl bg-slate-50 border border-gray-100 text-center space-y-3">
                        <i class="fa-regular fa-calendar-xmark text-3xl text-slate-300"></i>
                        <p class="text-xs font-medium text-slate-500">You don't have any upcoming sessions scheduled right now.</p>
                        <a href="{{ route('student.tutors.index') }}" class="inline-block bg-primary-800 hover:bg-primary-900 text-white text-xs font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all">
                            Browse Tutors & Book a Session
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingBookings as $booking)
                            <div class="p-4 sm:p-5 rounded-xl border border-gray-100 bg-slate-50/50 hover:border-primary-800/30 hover:shadow-md transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $booking->tutor->avatar_url }}" alt="{{ $booking->tutor->name }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-gray-100">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold font-heading text-slate-900">{{ $booking->tutor->name }}</h4>
                                            <x-booking-badge :status="$booking->status" />
                                        </div>
                                        <p class="text-xs font-semibold text-primary-800 mt-0.5">{{ $booking->subject }}</p>
                                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                                            <span><i class="fa-regular fa-calendar text-slate-400 mr-1"></i> {{ $booking->booking_date->format('M d, Y') }}</span>
                                            <span><i class="fa-regular fa-clock text-slate-400 mr-1"></i> {{ date('g:i A', strtotime($booking->start_time)) }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                                    <a href="{{ route('student.messages.show', $booking->tutor_id) }}" class="p-2.5 rounded-xl text-slate-600 hover:bg-slate-200 bg-white border border-gray-100" title="Chat with Tutor">
                                        <i class="fa-regular fa-comment"></i>
                                    </a>
                                    <a href="{{ route('student.bookings.show', $booking->id) }}" class="bg-primary-800 hover:bg-primary-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                                        Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recommended Tutors Section (Matching subjects_needed) -->
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">Recommended Mentors for You</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            @if(!empty($profile->subjects_needed))
                                Tutors specializing in {{ implode(', ', $profile->subjects_needed) }}
                            @else
                                Top-rated educators ready for 1-on-1 tutoring
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('student.tutors.index') }}" class="text-xs font-bold text-primary-800 hover:underline">
                        Explore All &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($recommendedTutors as $rec)
                        <div class="p-4 rounded-xl border border-gray-100 bg-slate-50 hover:bg-white hover:shadow-md transition-all flex items-start gap-3.5">
                            <img src="{{ $rec->user->avatar_url }}" alt="{{ $rec->user->name }}" class="w-12 h-12 rounded-xl object-cover">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold font-heading text-slate-900 truncate">{{ $rec->user->name }}</h4>
                                <p class="text-[11px] text-slate-500 truncate">{{ $rec->headline }}</p>
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-xs font-bold font-heading text-slate-900">PKR {{ number_format($rec->hourly_rate, 0) }}<span class="text-[10px] font-normal text-slate-400">/hr</span></span>
                                    <a href="{{ route('student.tutors.show', $rec->user_id) }}" class="text-[11px] font-bold text-primary-800 hover:underline">View &rarr;</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Col: Profile Summary & Quick Find Tutors -->
        <div class="space-y-6">
            <!-- Learning Profile Card -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">My Student Profile</h3>
                    <a href="{{ route('student.profile.edit') }}" class="text-xs font-bold text-primary-800 hover:underline">Edit</a>
                </div>
                
                <div class="flex items-center gap-3.5">
                    <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="w-14 h-14 rounded-xl object-cover ring-2 ring-gray-100">
                    <div>
                        <h4 class="text-sm font-bold font-heading text-slate-900">{{ $student->name }}</h4>
                        <p class="text-xs text-slate-500 font-medium">{{ $profile->grade_level ?: 'Grade not set' }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5"><i class="fa-solid fa-location-dot"></i> {{ $student->city ?: 'Location not set' }}</p>
                    </div>
                </div>

                @if(!empty($profile->subjects_needed))
                    <div class="pt-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Subjects Needed:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($profile->subjects_needed as $subj)
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-primary-50 text-primary-800">
                                    {{ $subj }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($profile->about)
                    <div class="p-3 rounded-xl bg-slate-50 border border-gray-100 text-xs text-slate-600 space-y-1">
                        <span class="font-bold text-slate-800 block">About / Goals:</span>
                        <p class="line-clamp-3 leading-relaxed">{{ $profile->about }}</p>
                    </div>
                @endif
            </div>

            <!-- Find Tutors CTA Card -->
            <div class="bg-gradient-to-br from-primary-800 to-emerald-700 text-white p-6 rounded-xl shadow-lg space-y-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h4 class="text-base font-bold font-heading">Looking for exam preparation?</h4>
                <p class="text-xs text-slate-100 leading-relaxed">Book a personalized 1-on-1 session with our top Math, Physics, Chemistry, and IELTS mentors.</p>
                <a href="{{ route('student.tutors.index') }}" class="inline-block w-full text-center bg-white hover:bg-slate-50 text-primary-800 font-bold text-xs py-3 rounded-xl shadow transition-all">
                    Find Verified Tutors
                </a>
            </div>
        </div>

    </div>
</div>
@endsection