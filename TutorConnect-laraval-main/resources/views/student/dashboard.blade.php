@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('header', 'Welcome, ' . $student->name)
@section('subheader', 'Here is a summary of your upcoming tutoring sessions and activities')

@section('content')
<div class="space-y-8">
    
    <!-- Stats Cards Grid (Reusable x-stat-card Components) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            icon="fa-solid fa-calendar-check" 
            :value="$stats['total_bookings']" 
            label="Total Bookings" 
            color="primary" 
        />
        <x-stat-card 
            icon="fa-regular fa-clock" 
            :value="$stats['upcoming_sessions']" 
            label="Upcoming Sessions" 
            color="emerald" 
        />
        <x-stat-card 
            icon="fa-solid fa-graduation-cap" 
            :value="$stats['completed_sessions']" 
            label="Completed Sessions" 
            color="blue" 
        />
        <x-stat-card 
            icon="fa-regular fa-comments" 
            :value="$stats['unread_messages']" 
            label="Unread Messages" 
            color="purple" 
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Upcoming Sessions -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">Upcoming Tutoring Sessions</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Your confirmed and scheduled classes</p>
                    </div>
                    <a href="{{ route('student.bookings.index') }}" class="text-xs font-bold text-primary-800 hover:underline">
                        View All ({{ $stats['total_bookings'] }})
                    </a>
                </div>

                @if($upcomingBookings->isEmpty())
                    <div class="p-8 rounded-xl bg-slate-50 border border-gray-100 text-center space-y-3">
                        <i class="fa-regular fa-calendar-xmark text-3xl text-slate-300"></i>
                        <p class="text-xs font-medium text-slate-500">You don't have any upcoming sessions scheduled.</p>
                        <a href="{{ route('tutors.index') }}" class="inline-block bg-primary-800 hover:bg-primary-900 text-white text-xs font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all">
                            Browse Tutors & Book a Class
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingBookings as $booking)
                            <div class="p-4 sm:p-5 rounded-xl border border-gray-100 hover:border-primary-800/30 hover:shadow-md transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-slate-50/50">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $booking->tutor->avatar_url }}" alt="{{ $booking->tutor->name }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-gray-100">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold font-heading text-slate-900">{{ $booking->tutor->name }}</h4>
                                            <x-booking-badge :status="$booking->status" />
                                        </div>
                                        <p class="text-xs font-semibold text-primary-800 mt-0.5">{{ $booking->subject }}</p>
                                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                                            <span><i class="fa-regular fa-calendar text-slate-400"></i> {{ $booking->booking_date->format('M d, Y') }}</span>
                                            <span><i class="fa-regular fa-clock text-slate-400"></i> {{ date('g:i A', strtotime($booking->start_time)) }}</span>
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

            <!-- Recommended Tutors Banner -->
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">Recommended Mentors for You</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Top-rated verified educators</p>
                    </div>
                    <a href="{{ route('tutors.index') }}" class="text-xs font-bold text-primary-800 hover:underline">Explore All</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($recommendedTutors as $rec)
                        <div class="p-4 rounded-xl border border-gray-100 bg-slate-50 hover:bg-white hover:shadow-md transition-all flex items-start gap-3.5">
                            <img src="{{ $rec->user->avatar_url }}" alt="{{ $rec->user->name }}" class="w-11 h-11 rounded-xl object-cover">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold font-heading text-slate-900 truncate">{{ $rec->user->name }}</h4>
                                <p class="text-[11px] text-slate-500 truncate">{{ $rec->headline }}</p>
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-xs font-bold font-heading text-slate-900">PKR {{ number_format($rec->hourly_rate, 0) }}<span class="text-[10px] font-normal text-slate-400">/hr</span></span>
                                    <a href="{{ route('tutors.show', $rec->user_id) }}" class="text-[11px] font-bold text-primary-800 hover:underline">View &rarr;</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Col: Profile Overview & Quick Actions -->
        <div class="space-y-6">
            <!-- Learning Profile Card -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">My Profile</h3>
                    <a href="{{ route('student.profile.edit') }}" class="text-xs font-bold text-primary-800 hover:underline">Edit</a>
                </div>
                <div class="flex items-center gap-3.5 pt-1">
                    <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="w-14 h-14 rounded-xl object-cover ring-2 ring-gray-100">
                    <div>
                        <h4 class="text-sm font-bold font-heading text-slate-900">{{ $student->name }}</h4>
                        <p class="text-xs text-slate-500">{{ $student->studentProfile->grade_level ?: 'Student' }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5"><i class="fa-solid fa-location-dot"></i> {{ $student->city ?: 'Location not set' }}</p>
                    </div>
                </div>

                @if($student->studentProfile->about)
                    <div class="p-3 rounded-xl bg-slate-50 border border-gray-100 text-xs text-slate-600">
                        <span class="font-bold text-slate-800 block mb-1">About / Goals:</span>
                        {{ $student->studentProfile->about }}
                    </div>
                @endif
            </div>

            <!-- Quick Action Links -->
            <div class="bg-gradient-to-br from-primary-800 to-emerald-700 text-white p-6 rounded-xl shadow-lg space-y-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h4 class="text-base font-bold font-heading">Need help with upcoming exams?</h4>
                <p class="text-xs text-slate-100 leading-relaxed">Book a personalized 1-on-1 session with top Calculus, Physics, and IELTS mentors.</p>
                <a href="{{ route('tutors.index') }}" class="inline-block w-full text-center bg-white hover:bg-slate-50 text-primary-800 font-bold text-xs py-3 rounded-xl shadow transition-all">
                    Search Verified Tutors
                </a>
            </div>
        </div>

    </div>
</div>
@endsection