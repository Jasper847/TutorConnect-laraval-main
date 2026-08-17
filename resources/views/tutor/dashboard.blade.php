@extends('layouts.app')

@section('title', 'Tutor Dashboard')
@section('header', 'Tutor Workspace')
@section('subheader', 'Manage student bookings, availability schedule, and performance')

@section('content')
<div class="space-y-8">
    
    <!-- Profile Completion Progress Bar -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-sparkles text-emerald-600"></i>
                <h3 class="text-sm font-bold font-heading text-slate-900">Profile Completion Status</h3>
            </div>
            <span class="text-xs font-bold font-heading {{ $completionPercentage >= 100 ? 'text-emerald-600' : 'text-primary-800' }}">
                {{ $completionPercentage }}% Complete
            </span>
        </div>

        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
            <div class="h-full rounded-full transition-all duration-500 {{ $completionPercentage >= 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-primary-800 to-emerald-500' }}" 
                 style="width: {{ $completionPercentage }}%"></div>
        </div>

        @if($completionPercentage < 100)
            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-500 gap-2 pt-1">
                <p>Complete your bio (50+ chars), subjects, education, hourly rate, and weekly schedule to get more bookings.</p>
                <a href="{{ route('tutor.profile.edit') }}" class="font-semibold text-primary-800 hover:underline shrink-0">
                    Complete Profile &rarr;
                </a>
            </div>
        @else
            <p class="text-xs text-emerald-700 font-medium">Your profile is 100% complete and fully visible in the tutor directory!</p>
        @endif
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            icon="fa-solid fa-calendar-check" 
            :value="$stats['total_bookings']" 
            label="Total Bookings" 
            color="primary" 
        />
        <x-stat-card 
            icon="fa-solid fa-hourglass-half" 
            :value="$stats['pending_bookings']" 
            label="Pending Bookings" 
            color="amber" 
            subtext="Awaiting your confirmation"
        />
        <x-stat-card 
            icon="fa-solid fa-graduation-cap" 
            :value="$stats['completed_sessions']" 
            label="Completed Sessions" 
            color="purple" 
        />
        <x-stat-card 
            icon="fa-solid fa-star" 
            :value="number_format($stats['avg_rating'], 1)" 
            label="Average Rating" 
            color="emerald" 
            :subtext="'(' . $stats['reviews_count'] . ' reviews)'"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Upcoming Bookings Table (Next 5 Sessions) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">Upcoming Bookings (Next 5 Sessions)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Confirmed and pending appointments</p>
                    </div>
                    <a href="{{ route('tutor.bookings.index') }}" class="text-xs font-bold text-primary-800 hover:underline">
                        View All ({{ $stats['total_bookings'] }})
                    </a>
                </div>

                @if($upcomingBookings->isEmpty())
                    <div class="p-8 rounded-xl bg-slate-50 text-center text-xs text-slate-400 space-y-2">
                        <i class="fa-regular fa-calendar-check text-3xl text-slate-300"></i>
                        <p>No upcoming tutoring sessions scheduled right now.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 border-b border-gray-100 text-slate-400 uppercase font-semibold">
                                <tr>
                                    <th class="p-3">Student</th>
                                    <th class="p-3">Subject</th>
                                    <th class="p-3">Date & Time</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($upcomingBookings as $booking)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="p-3">
                                            <div class="flex items-center gap-2.5">
                                                <img src="{{ $booking->student->avatar_url }}" alt="{{ $booking->student->name }}" class="w-8 h-8 rounded-lg object-cover">
                                                <div>
                                                    <p class="font-bold text-slate-900">{{ $booking->student->name }}</p>
                                                    <p class="text-[10px] text-slate-400">#{{ $booking->booking_code }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-3 font-semibold text-slate-800">{{ $booking->subject }}</td>
                                        <td class="p-3 text-slate-500">
                                            <div class="font-medium text-slate-800">{{ $booking->booking_date->format('M d, Y') }}</div>
                                            <div class="text-[10px] text-slate-400">{{ date('g:i A', strtotime($booking->start_time)) }}</div>
                                        </td>
                                        <td class="p-3">
                                            <x-booking-badge :status="$booking->status" />
                                        </td>
                                        <td class="p-3 text-right space-x-1">
                                            @if($booking->status === 'pending')
                                                <form method="POST" action="{{ route('tutor.bookings.confirm', $booking->id) }}" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg shadow-sm transition-all">
                                                        Confirm
                                                    </button>
                                                </form>
                                            @elseif($booking->status === 'confirmed')
                                                <form method="POST" action="{{ route('tutor.bookings.complete', $booking->id) }}" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="bg-primary-800 hover:bg-primary-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg shadow-sm transition-all">
                                                        Complete
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('tutor.messages.show', $booking->student_id) }}" class="inline-block p-1 text-slate-500 hover:text-primary-800" title="Message Student">
                                                <i class="fa-regular fa-comment"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Col: Recent Reviews (Last 3) & Quick Links -->
        <div class="space-y-6">
            
            <!-- Recent Reviews -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Recent Student Reviews</h3>
                    <a href="{{ route('tutor.reviews.index') }}" class="text-xs font-bold text-primary-800 hover:underline">All Reviews</a>
                </div>

                @if($recentReviews->isEmpty())
                    <p class="text-xs text-slate-400 py-4 text-center">No student reviews received yet.</p>
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
                                <p class="text-xs text-slate-600 italic line-clamp-2 leading-relaxed">"{{ $rev->comment }}"</p>
                                <span class="text-[10px] text-slate-400 block">{{ $rev->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Quick Access Card -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-3 text-xs">
                <h4 class="font-bold font-heading text-slate-900 uppercase tracking-wider text-[11px] text-slate-400">Quick Actions</h4>
                <div class="space-y-2">
                    <a href="{{ route('tutor.profile.edit') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-semibold text-slate-800 transition-colors">
                        <span class="flex items-center gap-2"><i class="fa-regular fa-id-badge text-primary-800"></i> Edit Profile & Rates</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    <a href="{{ route('tutor.availability.index') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-semibold text-slate-800 transition-colors">
                        <span class="flex items-center gap-2"><i class="fa-regular fa-clock text-emerald-600"></i> Weekly Availability Matrix</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    <a href="{{ route('tutor.bookings.index') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 font-semibold text-slate-800 transition-colors">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-calendar-check text-blue-600"></i> All Bookings ({{ $stats['total_bookings'] }})</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection