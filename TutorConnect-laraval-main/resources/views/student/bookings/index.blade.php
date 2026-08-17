@extends('layouts.dashboard')

@section('title', 'My Bookings')
@section('header', 'My Tutoring Bookings')
@section('subheader', 'Track all your upcoming, pending, and past sessions')

@section('content')
<div class="space-y-6">
    
    <!-- Tab Filters -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('student.bookings.index', ['tab' => 'all']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'all' ? 'bg-brand-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            All Bookings ({{ $counts['all'] }})
        </a>
        <a href="{{ route('student.bookings.index', ['tab' => 'upcoming']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'upcoming' ? 'bg-brand-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            Upcoming ({{ $counts['upcoming'] }})
        </a>
        <a href="{{ route('student.bookings.index', ['tab' => 'completed']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'completed' ? 'bg-brand-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            Completed ({{ $counts['completed'] }})
        </a>
        <a href="{{ route('student.bookings.index', ['tab' => 'cancelled']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'cancelled' ? 'bg-brand-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            Cancelled ({{ $counts['cancelled'] }})
        </a>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white p-12 rounded-3xl border border-slate-200/80 text-center space-y-4 shadow-sm">
            <i class="fa-regular fa-calendar-xmark text-4xl text-slate-300"></i>
            <h3 class="text-base font-bold text-slate-900">No bookings found in this view</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Explore our directory of verified mentors and book your first 1-on-1 session.</p>
            <a href="{{ route('tutors.index') }}" class="inline-block bg-brand-800 text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-sm">
                Find Tutors
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <!-- Left info -->
                    <div class="flex items-start gap-4">
                        <img src="{{ $booking->tutor->avatar_url }}" alt="{{ $booking->tutor->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100 shrink-0">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-bold text-slate-400">#{{ $booking->booking_code }}</span>
                                @if($booking->status === 'confirmed')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Confirmed</span>
                                @elseif($booking->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Pending</span>
                                @elseif($booking->status === 'completed')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">Completed</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">Cancelled</span>
                                @endif
                                <span class="text-xs font-semibold text-slate-500 uppercase">{{ ucfirst($booking->mode) }}</span>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 mt-1">
                                {{ $booking->tutor->name }} — <span class="text-brand-800 font-semibold">{{ $booking->subject?->name ?? 'General Session' }}</span>
                            </h3>

                            <div class="mt-2 flex flex-wrap items-center gap-4 text-xs font-medium text-slate-500">
                                <span><i class="fa-regular fa-calendar text-slate-400"></i> {{ $booking->booking_date->format('l, M d, Y') }}</span>
                                <span><i class="fa-regular fa-clock text-slate-400"></i> {{ date('g:i A', strtotime($booking->start_time)) }} - {{ date('g:i A', strtotime($booking->end_time)) }}</span>
                                <span><i class="fa-solid fa-dollar-sign text-slate-400"></i> ${{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto justify-end border-t lg:border-t-0 pt-3 lg:pt-0">
                        <a href="{{ route('student.messages.show', $booking->tutor_id) }}" class="p-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold" title="Message Tutor">
                            <i class="fa-regular fa-comment"></i>
                        </a>

                        @if($booking->canBeReviewedBy(auth()->id()))
                            <a href="{{ route('student.reviews.create', $booking->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm">
                                ⭐ Leave Review
                            </a>
                        @endif

                        @if($booking->status === 'pending' && !$booking->payment)
                            <a href="{{ route('student.payment.checkout', $booking->id) }}" class="bg-accent-600 hover:bg-accent-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm">
                                Pay & Confirm
                            </a>
                        @endif

                        <a href="{{ route('student.bookings.show', $booking->id) }}" class="bg-brand-800 hover:bg-brand-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
