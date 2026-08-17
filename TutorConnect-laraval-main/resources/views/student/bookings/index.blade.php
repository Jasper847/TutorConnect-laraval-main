@extends('layouts.app')

@section('title', 'My Bookings & Classes')
@section('header', 'My Bookings & Sessions')
@section('subheader', 'Track all your scheduled 1-on-1 tutoring sessions, payments, and reviews')

@section('content')
<div class="space-y-6">
    
    <!-- Status Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 pb-3">
        <a href="{{ route('student.bookings.index', ['status' => 'all']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-all {{ $status === 'all' ? 'bg-primary-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-gray-100' }}">
            All Sessions ({{ $counts['all'] }})
        </a>
        <a href="{{ route('student.bookings.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-all {{ $status === 'pending' ? 'bg-primary-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-gray-100' }}">
            Pending ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('student.bookings.index', ['status' => 'confirmed']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-all {{ $status === 'confirmed' ? 'bg-primary-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-gray-100' }}">
            Confirmed ({{ $counts['confirmed'] }})
        </a>
        <a href="{{ route('student.bookings.index', ['status' => 'completed']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-all {{ $status === 'completed' ? 'bg-primary-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-gray-100' }}">
            Completed ({{ $counts['completed'] }})
        </a>
        <a href="{{ route('student.bookings.index', ['status' => 'cancelled']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-all {{ $status === 'cancelled' ? 'bg-primary-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-gray-100' }}">
            Cancelled ({{ $counts['cancelled'] }})
        </a>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white p-12 rounded-xl border border-gray-100 text-center space-y-3 shadow-sm">
            <i class="fa-regular fa-calendar-xmark text-4xl text-slate-300"></i>
            <h3 class="text-base font-bold font-heading text-slate-900">No bookings in this category</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Book a 1-on-1 session with a certified educator to get started.</p>
            <a href="{{ route('student.tutors.index') }}" class="inline-block bg-primary-800 hover:bg-primary-900 text-white font-semibold text-xs px-5 py-2.5 rounded-xl shadow-sm transition-all">
                Browse Expert Tutors
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    
                    <!-- Left: Tutor info & Session date/time -->
                    <div class="flex items-start gap-4">
                        <img src="{{ $booking->tutor->avatar_url }}" alt="{{ $booking->tutor->name }}" class="w-14 h-14 rounded-xl object-cover ring-2 ring-gray-100 shrink-0">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-bold font-mono text-slate-400">#{{ $booking->booking_code }}</span>
                                <x-booking-badge :status="$booking->status" />
                                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">{{ ucfirst($booking->mode) }}</span>
                            </div>

                            <h3 class="text-base font-bold font-heading text-slate-900 mt-1">
                                {{ $booking->tutor->name }} — <span class="text-primary-800 font-semibold">{{ $booking->subject }}</span>
                            </h3>

                            <div class="mt-2 flex flex-wrap items-center gap-4 text-xs font-medium text-slate-500">
                                <span><i class="fa-regular fa-calendar text-slate-400 mr-1"></i> {{ $booking->booking_date->format('l, M d, Y') }}</span>
                                <span><i class="fa-regular fa-clock text-slate-400 mr-1"></i> {{ date('g:i A', strtotime($booking->start_time)) }} - {{ date('g:i A', strtotime($booking->end_time)) }}</span>
                                <span class="font-bold text-slate-800"><i class="fa-solid fa-money-bill-wave text-emerald-600 mr-1"></i> PKR {{ number_format($booking->total_amount, 0) }}</span>
                            </div>

                            @if($booking->notes)
                                <div class="mt-2 text-xs text-slate-600 italic bg-slate-50 p-2.5 rounded-lg border border-gray-100">
                                    <span class="font-bold not-italic text-slate-700">Notes:</span> "{{ $booking->notes }}"
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Action Buttons -->
                    <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto justify-end border-t lg:border-t-0 pt-3 lg:pt-0">
                        <a href="{{ route('student.messages.show', $booking->tutor_id) }}" class="p-2.5 rounded-xl border border-gray-200 text-slate-600 hover:bg-slate-50 text-xs" title="Message Tutor">
                            <i class="fa-regular fa-comment text-sm"></i>
                        </a>

                        @if($booking->status === 'pending')
                            <a href="{{ route('student.payment.checkout', $booking->id) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                                <i class="fa-solid fa-credit-card"></i> Pay Now (Sandbox)
                            </a>
                        @elseif($booking->status === 'completed')
                            @if(!$booking->review)
                                <a href="{{ route('student.reviews.create', $booking->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                                    <i class="fa-solid fa-star"></i> Leave Review
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-star text-amber-400"></i> Reviewed ({{ $booking->review->rating }}★)
                                </span>
                            @endif
                        @endif

                        <a href="{{ route('student.bookings.show', $booking->id) }}" class="bg-primary-800 hover:bg-primary-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all">
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
