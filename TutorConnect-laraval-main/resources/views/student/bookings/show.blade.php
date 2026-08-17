@extends('layouts.dashboard')

@section('title', 'Booking Details - #' . $booking->booking_code)
@section('header', 'Booking Details')
@section('subheader', 'Reference #' . $booking->booking_code)

@section('content')
<div class="max-w-4xl space-y-6">
    
    <!-- Top Action Banner -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            @if($booking->status === 'confirmed')
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Session Confirmed</h3>
                    <p class="text-xs text-slate-500">Your appointment is scheduled and ready.</p>
                </div>
            @elseif($booking->status === 'pending')
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Booking Pending</h3>
                    <p class="text-xs text-slate-500">Awaiting confirmation from your tutor.</p>
                </div>
            @elseif($booking->status === 'completed')
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-800 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Session Completed</h3>
                    <p class="text-xs text-slate-500">This class has been successfully finished.</p>
                </div>
            @else
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-800 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Booking Cancelled</h3>
                    <p class="text-xs text-slate-500">{{ $booking->cancellation_reason ?: 'Session was cancelled.' }}</p>
                </div>
            @endif
        </div>

        <!-- Action CTAs -->
        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <a href="{{ route('student.messages.show', $booking->tutor_id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold px-4 py-2.5 rounded-xl transition-all flex items-center gap-2">
                <i class="fa-regular fa-comment"></i>
                <span>Chat with Tutor</span>
            </a>

            @if($booking->canBeReviewedBy(auth()->id()))
                <a href="{{ route('student.reviews.create', $booking->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-star"></i>
                    <span>Leave Review</span>
                </a>
            @endif

            @if(in_array($booking->status, ['pending', 'confirmed']))
                <button type="button" @click="$dispatch('open-cancel-modal')" class="bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold px-4 py-2.5 rounded-xl transition-all">
                    Cancel Session
                </button>
            @endif
        </div>
    </div>

    <!-- Booking Overview Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Session Specs -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
            <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Session Information</h4>
            <div class="space-y-3 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Subject</span>
                    <span class="font-bold text-slate-900">{{ $booking->subject?->name ?? 'General Session' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Date</span>
                    <span class="font-bold text-slate-900">{{ $booking->booking_date->format('l, M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Time Window</span>
                    <span class="font-bold text-slate-900">{{ date('g:i A', strtotime($booking->start_time)) }} - {{ date('g:i A', strtotime($booking->end_time)) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Mode</span>
                    <span class="font-bold text-slate-900 uppercase">{{ $booking->mode }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Total Amount</span>
                    <span class="font-extrabold text-brand-800 text-sm">${{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>

            @if($booking->student_notes)
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <span class="text-[11px] font-bold uppercase text-slate-400">Your Notes to Tutor:</span>
                    <p class="text-xs text-slate-600 italic mt-1">"{{ $booking->student_notes }}"</p>
                </div>
            @endif
        </div>

        <!-- Tutor Details Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
            <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Tutor Information</h4>
            <div class="flex items-center gap-4">
                <img src="{{ $booking->tutor->avatar_url }}" alt="{{ $booking->tutor->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100">
                <div>
                    <h5 class="text-sm font-bold text-slate-900">{{ $booking->tutor->name }}</h5>
                    <p class="text-xs text-slate-500">{{ $booking->tutor->tutorProfile->qualification }}</p>
                    <p class="text-xs text-brand-800 font-semibold mt-0.5">{{ $booking->tutor->email }}</p>
                </div>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-600 space-y-1">
                <p><strong>Experience:</strong> {{ $booking->tutor->tutorProfile->experience_years }} years teaching</p>
                <p><strong>Rating:</strong> ⭐ {{ number_format($booking->tutor->tutorProfile->rating_cache, 1) }} / 5.0 ({{ $booking->tutor->tutorProfile->reviews_count }} reviews)</p>
            </div>
        </div>

    </div>

    <!-- Review Section if present -->
    @if($booking->review)
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-3">
            <h4 class="text-sm font-bold text-slate-900">Your Review for this Session</h4>
            <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200/60 space-y-2">
                <div class="flex items-center text-amber-400 text-xs">
                    @for($i=1; $i<=5; $i++)
                        <i class="fa-solid fa-star {{ $i <= $booking->review->rating ? 'text-amber-400' : 'text-slate-300' }}"></i>
                    @endfor
                </div>
                <p class="text-xs text-slate-700 italic">"{{ $booking->review->comment }}"</p>
            </div>
        </div>
    @endif

    <!-- Cancel Modal (Alpine.js) -->
    <div x-data="{ open: false }" 
         x-on:open-cancel-modal.window="open = true" 
         x-show="open" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-2xl max-w-md w-full relative z-10 space-y-4">
            <h3 class="text-lg font-bold text-slate-900">Cancel Tutoring Booking</h3>
            <p class="text-xs text-slate-500">Please provide a reason for cancelling this session. Your tutor will be notified via email.</p>

            <form method="POST" action="{{ route('student.bookings.cancel', $booking->id) }}" class="space-y-4">
                @csrf
                <textarea name="cancellation_reason" rows="3" required placeholder="Reason for cancellation..."
                          class="w-full text-xs font-medium p-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none"></textarea>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">
                        Keep Session
                    </button>
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-5 py-2 rounded-xl shadow">
                        Confirm Cancellation
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
