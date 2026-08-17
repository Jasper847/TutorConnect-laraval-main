@extends('layouts.app')

@section('title', 'Manage Tutor Bookings')
@section('header', 'Student Booking Requests')
@section('subheader', 'Confirm incoming session requests, manage scheduled classes, and mark completed sessions')

@section('content')
<div class="space-y-6">
    
    <!-- Status Tabs Navigation -->
    <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 pb-3">
        <a href="{{ route('tutor.bookings.index', ['status' => 'all']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-all {{ $status === 'all' ? 'bg-primary-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-gray-100' }}">
            All Bookings ({{ $counts['all'] }})
        </a>
        <a href="{{ route('tutor.bookings.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-all {{ $status === 'pending' ? 'bg-primary-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-gray-100' }}">
            Pending Requests ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('tutor.bookings.index', ['status' => 'confirmed']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-all {{ $status === 'confirmed' ? 'bg-primary-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-gray-100' }}">
            Confirmed ({{ $counts['confirmed'] }})
        </a>
        <a href="{{ route('tutor.bookings.index', ['status' => 'completed']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-all {{ $status === 'completed' ? 'bg-primary-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-gray-100' }}">
            Completed ({{ $counts['completed'] }})
        </a>
        <a href="{{ route('tutor.bookings.index', ['status' => 'cancelled']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-all {{ $status === 'cancelled' ? 'bg-primary-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-gray-100' }}">
            Cancelled ({{ $counts['cancelled'] }})
        </a>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white p-12 rounded-xl border border-gray-100 text-center space-y-3 shadow-sm">
            <i class="fa-regular fa-calendar-xmark text-4xl text-slate-300"></i>
            <h3 class="text-base font-bold font-heading text-slate-900">No bookings found in this view</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">When students book tutoring sessions with you, they will show up here.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6"
                     x-data="{ cancelModal: false }">
                    
                    <!-- Left Student & Booking Info -->
                    <div class="flex items-start gap-4">
                        <img src="{{ $booking->student->avatar_url }}" alt="{{ $booking->student->name }}" class="w-14 h-14 rounded-xl object-cover ring-2 ring-gray-100 shrink-0">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-bold font-mono text-slate-400">#{{ $booking->booking_code }}</span>
                                <x-booking-badge :status="$booking->status" />
                                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">{{ ucfirst($booking->mode) }}</span>
                            </div>

                            <h3 class="text-base font-bold font-heading text-slate-900 mt-1">
                                {{ $booking->student->name }} — <span class="text-primary-800 font-semibold">{{ $booking->subject }}</span>
                            </h3>

                            <div class="mt-2 flex flex-wrap items-center gap-4 text-xs font-medium text-slate-500">
                                <span><i class="fa-regular fa-calendar text-slate-400 mr-1"></i> {{ $booking->booking_date->format('l, M d, Y') }}</span>
                                <span><i class="fa-regular fa-clock text-slate-400 mr-1"></i> {{ date('g:i A', strtotime($booking->start_time)) }} - {{ date('g:i A', strtotime($booking->end_time)) }}</span>
                                <span class="font-bold text-slate-800"><i class="fa-solid fa-money-bill-wave text-emerald-600 mr-1"></i> PKR {{ number_format($booking->total_amount, 0) }}</span>
                            </div>

                            @if($booking->notes)
                                <div class="mt-2 text-xs text-slate-600 italic bg-slate-50 p-2.5 rounded-lg border border-gray-100">
                                    <span class="font-bold not-italic text-slate-700">Student Notes:</span> "{{ $booking->notes }}"
                                </div>
                            @endif

                            @if($booking->cancellation_reason)
                                <div class="mt-2 text-xs text-rose-700 bg-rose-50 p-2.5 rounded-lg border border-rose-100">
                                    <span class="font-bold">Cancellation Reason:</span> {{ $booking->cancellation_reason }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Action Buttons -->
                    <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto justify-end border-t lg:border-t-0 pt-3 lg:pt-0">
                        <a href="{{ route('tutor.messages.show', $booking->student_id) }}" class="p-2.5 rounded-xl border border-gray-200 text-slate-600 hover:bg-slate-50 text-xs" title="Message Student">
                            <i class="fa-regular fa-comment text-sm"></i>
                        </a>

                        @if($booking->status === 'pending')
                            <!-- Confirm Button (pending -> confirmed) -->
                            <form method="POST" action="{{ route('tutor.bookings.confirm', $booking->id) }}">
                                @csrf
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-1.5">
                                    <i class="fa-solid fa-check"></i>
                                    <span>Confirm Booking</span>
                                </button>
                            </form>

                            <!-- Cancel Button -->
                            <button type="button" @click="cancelModal = true" class="bg-slate-100 hover:bg-slate-200 text-rose-600 text-xs font-semibold px-3.5 py-2.5 rounded-xl transition-all">
                                Decline
                            </button>

                        @elseif($booking->status === 'confirmed')
                            <!-- Mark Complete Button (confirmed -> completed) -->
                            <form method="POST" action="{{ route('tutor.bookings.complete', $booking->id) }}">
                                @csrf
                                <button type="submit" class="bg-primary-800 hover:bg-primary-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Mark as Completed</span>
                                </button>
                            </form>

                            <button type="button" @click="cancelModal = true" class="bg-slate-100 hover:bg-slate-200 text-rose-600 text-xs font-semibold px-3.5 py-2.5 rounded-xl transition-all">
                                Cancel
                            </button>
                        @endif
                    </div>

                    <!-- Cancel Modal -->
                    <div x-show="cancelModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="cancelModal = false"></div>
                        <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-2xl max-w-md w-full relative z-10 space-y-4">
                            <h3 class="text-base font-bold font-heading text-slate-900">Cancel Booking #{{ $booking->booking_code }}</h3>
                            <p class="text-xs text-slate-500">Please provide a reason for cancelling this session. The student will be notified.</p>

                            <form method="POST" action="{{ route('tutor.bookings.cancel', $booking->id) }}" class="space-y-4">
                                @csrf
                                <textarea name="cancellation_reason" rows="3" required placeholder="Reason for cancellation..."
                                          class="w-full text-xs font-medium p-3 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none"></textarea>

                                <div class="flex justify-end gap-2 pt-2">
                                    <button type="button" @click="cancelModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600">Keep Booking</button>
                                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow">Confirm Cancellation</button>
                                </div>
                            </form>
                        </div>
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
