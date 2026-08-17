@extends('layouts.dashboard')

@section('title', 'Payment & Booking Confirmed')
@section('header', 'Payment Successful')
@section('subheader', 'Your 1-on-1 tutoring session has been confirmed')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xl text-center space-y-6">
        
        <!-- Success Check Icon -->
        <div class="w-20 h-20 mx-auto rounded-3xl bg-emerald-50 text-accent-600 flex items-center justify-center text-4xl shadow-inner">
            <i class="fa-solid fa-circle-check animate-bounce"></i>
        </div>

        <div class="space-y-1">
            <h2 class="text-2xl font-extrabold text-slate-900">Session Confirmed!</h2>
            <p class="text-xs text-slate-500">Booking Reference: <span class="font-bold text-slate-900">#{{ $booking->booking_code }}</span></p>
        </div>

        <!-- Receipt Card -->
        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-left space-y-3 text-xs">
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500 font-medium">Tutor</span>
                <span class="font-bold text-slate-900">{{ $booking->tutor->name }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500 font-medium">Subject</span>
                <span class="font-bold text-slate-900">{{ $booking->subject?->name ?? 'General Session' }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500 font-medium">Scheduled Time</span>
                <span class="font-bold text-slate-900">{{ $booking->booking_date->format('l, M d, Y') }} at {{ date('g:i A', strtotime($booking->start_time)) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 font-medium">Amount Paid (Sandbox)</span>
                <span class="font-extrabold text-emerald-700 text-sm">${{ number_format($booking->total_amount, 2) }}</span>
            </div>
        </div>

        <p class="text-xs text-slate-500">
            A confirmation email has been dispatched to <strong>{{ $booking->student->email }}</strong> and your tutor.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <a href="{{ route('student.bookings.show', $booking->id) }}" class="flex-1 bg-brand-800 hover:bg-brand-900 text-white font-bold text-xs py-3.5 rounded-xl shadow transition-all">
                View Booking Details
            </a>
            <a href="{{ route('student.messages.show', $booking->tutor_id) }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs py-3.5 rounded-xl transition-all">
                Message Tutor
            </a>
        </div>
    </div>
</div>
@endsection
