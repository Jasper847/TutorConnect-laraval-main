@extends('layouts.app')

@section('title', 'Tutor Dashboard')
@section('header', 'Tutor Workspace')
@section('subheader', 'Manage student bookings, availability schedules, and earnings')

@section('content')
<div class="space-y-8">
    
    <!-- Tutor Stats Overview (Reusable x-stat-card Components) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            icon="fa-solid fa-users" 
            :value="$stats['total_students']" 
            label="Total Students" 
            color="primary" 
        />
        <x-stat-card 
            icon="fa-solid fa-dollar-sign" 
            :value="'PKR ' . number_format($stats['total_earnings'], 0)" 
            label="Demo Earnings" 
            color="emerald" 
        />
        <x-stat-card 
            icon="fa-solid fa-star" 
            :value="number_format($stats['rating'], 1)" 
            label="Average Rating" 
            color="amber" 
            :subtext="'(' . $stats['reviews_count'] . ' reviews)'"
        />
        <x-stat-card 
            icon="fa-solid fa-graduation-cap" 
            :value="$stats['completed_sessions']" 
            label="Completed Sessions" 
            color="purple" 
        />
    </div>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Pending Requests & Upcoming Sessions -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Pending Requests Queue -->
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">New Booking Requests</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Students awaiting your session confirmation</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                        {{ $pendingBookings->count() }} Pending
                    </span>
                </div>

                @if($pendingBookings->isEmpty())
                    <div class="p-6 rounded-xl bg-slate-50 text-center text-xs text-slate-400">
                        <i class="fa-regular fa-calendar-check text-2xl text-slate-300 mb-2"></i>
                        <p>No new pending booking requests at this moment.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($pendingBookings as $pBooking)
                            <div class="p-4 sm:p-5 rounded-xl border border-gray-100 bg-slate-50/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <img src="{{ $pBooking->student->avatar_url }}" alt="{{ $pBooking->student->name }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-gray-100">
                                    <div>
                                        <h4 class="text-sm font-bold font-heading text-slate-900">{{ $pBooking->student->name }}</h4>
                                        <p class="text-xs text-primary-800 font-semibold">{{ $pBooking->subject }} — PKR {{ number_format($pBooking->total_amount, 0) }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            {{ $pBooking->booking_date->format('M d, Y') }} at {{ date('g:i A', strtotime($pBooking->start_time)) }} ({{ ucfirst($pBooking->mode) }})
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                                    <form method="POST" action="{{ route('tutor.bookings.confirm', $pBooking->id) }}">
                                        @csrf
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition-all">
                                            Accept & Confirm
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('tutor.bookings.cancel', $pBooking->id) }}">
                                        @csrf
                                        <input type="hidden" name="cancellation_reason" value="Tutor schedule conflict">
                                        <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-rose-600 text-xs font-semibold px-3 py-2 rounded-xl transition-all">
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Upcoming Confirmed Sessions -->
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">Upcoming Confirmed Classes</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Scheduled sessions ready to teach</p>
                    </div>
                    <a href="{{ route('tutor.bookings.index') }}" class="text-xs font-bold text-primary-800 hover:underline">
                        View All
                    </a>
                </div>

                @if($upcomingSessions->isEmpty())
                    <div class="p-6 rounded-xl bg-slate-50 text-center text-xs text-slate-400">
                        <p>No upcoming confirmed sessions scheduled.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingSessions as $uSession)
                            <div class="p-4 rounded-xl border border-gray-100 bg-slate-50/30 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <img src="{{ $uSession->student->avatar_url }}" alt="{{ $uSession->student->name }}" class="w-10 h-10 rounded-xl object-cover">
                                    <div>
                                        <h4 class="text-xs font-bold font-heading text-slate-900">{{ $uSession->student->name }}</h4>
                                        <p class="text-[11px] text-slate-500">
                                            {{ $uSession->booking_date->format('M d, Y') }} at {{ date('g:i A', strtotime($uSession->start_time)) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('tutor.messages.show', $uSession->student_id) }}" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 border border-gray-100 text-xs">
                                        <i class="fa-regular fa-comment"></i>
                                    </a>
                                    <form method="POST" action="{{ route('tutor.bookings.complete', $uSession->id) }}">
                                        @csrf
                                        <button type="submit" class="bg-primary-800 hover:bg-primary-900 text-white text-xs font-semibold px-3.5 py-1.5 rounded-xl shadow-sm">
                                            Mark Completed
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Right Col: Profile & Reviews Sidebar -->
        <div class="space-y-6">
            
            <!-- Tutor Profile Status Card -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Profile Status</h3>
                    @if($tutor->tutorProfile->is_verified)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Verified</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Pending Review</span>
                    @endif
                </div>

                <div class="flex items-center gap-3.5 pt-1">
                    <img src="{{ $tutor->avatar_url }}" alt="{{ $tutor->name }}" class="w-14 h-14 rounded-xl object-cover ring-2 ring-gray-100">
                    <div>
                        <h4 class="text-sm font-bold font-heading text-slate-900">{{ $tutor->name }}</h4>
                        <p class="text-xs text-primary-800 font-semibold">PKR {{ number_format($tutor->tutorProfile->hourly_rate, 0) }} / hour</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">{{ $tutor->city ?: 'Worldwide Online' }}</p>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex flex-col gap-2">
                    <a href="{{ route('tutor.profile.edit') }}" class="w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs py-2.5 rounded-xl transition-all">
                        Edit Profile & Rates
                    </a>
                    <a href="{{ route('tutor.availability.index') }}" class="w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs py-2.5 rounded-xl transition-all">
                        Manage Availability Slots
                    </a>
                </div>
            </div>

            <!-- Recent Reviews Card -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Recent Feedback</h3>
                    <a href="{{ route('tutor.reviews.index') }}" class="text-xs font-bold text-primary-800 hover:underline">All Reviews</a>
                </div>

                @if($recentReviews->isEmpty())
                    <p class="text-xs text-slate-400 py-4 text-center">No reviews received yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($recentReviews as $rev)
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-gray-100 space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-900">{{ $rev->student->name }}</span>
                                    <div class="flex items-center text-amber-400 text-[10px]">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-amber-400' : 'text-slate-300' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 italic line-clamp-2">"{{ $rev->comment }}"</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection