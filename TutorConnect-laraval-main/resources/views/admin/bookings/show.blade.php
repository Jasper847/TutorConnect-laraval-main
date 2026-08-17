@extends('layouts.dashboard')

@section('title', 'Admin Booking Audit #' . $booking->booking_code)
@section('header', 'Booking Audit #' . $booking->booking_code)
@section('subheader', 'Complete transaction, participants, and status overview')

@section('content')
<div class="max-w-4xl space-y-6">
    
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase">Status</span>
            <div class="flex items-center gap-2 mt-1">
                <h3 class="text-xl font-extrabold text-slate-900 capitalize">{{ $booking->status }}</h3>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : ($booking->status === 'completed' ? 'bg-blue-100 text-blue-800' : ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800')) }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
        </div>

        @if(in_array($booking->status, ['pending', 'confirmed']))
            <form method="POST" action="{{ route('admin.bookings.cancel', $booking->id) }}" onsubmit="return confirm('Force cancel this session as Administrator?')">
                @csrf
                <input type="hidden" name="cancellation_reason" value="Cancelled by Administrator moderation">
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow transition-all">
                    Force Cancel Booking
                </button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Student Info -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Student Profile</h4>
            <div class="flex items-center gap-3.5">
                <img src="{{ $booking->student->avatar_url }}" alt="{{ $booking->student->name }}" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-slate-100">
                <div>
                    <h5 class="text-sm font-bold text-slate-900">{{ $booking->student->name }}</h5>
                    <p class="text-xs text-slate-500">{{ $booking->student->email }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $booking->student->phone ?: 'No phone' }}</p>
                </div>
            </div>
        </div>

        <!-- Tutor Info -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Tutor Profile</h4>
            <div class="flex items-center gap-3.5">
                <img src="{{ $booking->tutor->avatar_url }}" alt="{{ $booking->tutor->name }}" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-slate-100">
                <div>
                    <h5 class="text-sm font-bold text-slate-900">{{ $booking->tutor->name }}</h5>
                    <p class="text-xs text-slate-500">{{ $booking->tutor->email }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $booking->tutor->phone ?: 'No phone' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Specs & Payment -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
        <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Session & Payment Transaction</h4>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-slate-400">Subject:</span>
                <p class="font-bold text-slate-900 mt-0.5">{{ $booking->subject?->name ?? 'General' }}</p>
            </div>
            <div>
                <span class="text-slate-400">Date:</span>
                <p class="font-bold text-slate-900 mt-0.5">{{ $booking->booking_date->format('M d, Y') }}</p>
            </div>
            <div>
                <span class="text-slate-400">Time:</span>
                <p class="font-bold text-slate-900 mt-0.5">{{ date('g:i A', strtotime($booking->start_time)) }} - {{ date('g:i A', strtotime($booking->end_time)) }}</p>
            </div>
            <div>
                <span class="text-slate-400">Total Fee:</span>
                <p class="font-bold text-emerald-700 mt-0.5">${{ number_format($booking->total_amount, 2) }}</p>
            </div>
        </div>

        @if($booking->payment)
            <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-600 flex justify-between items-center bg-slate-50 p-4 rounded-2xl">
                <div>
                    <span class="font-bold text-slate-900">Stripe Sandbox Payment Intent:</span>
                    <p class="font-mono text-[11px] text-slate-500 mt-0.5">{{ $booking->payment->stripe_payment_intent_id }}</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                    {{ ucfirst($booking->payment->status) }}
                </span>
            </div>
        @endif
    </div>

</div>
@endsection
