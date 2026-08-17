@extends('layouts.app')

@section('title', 'Payment Confirmed — #' . $booking->booking_code)
@section('header', 'Payment Confirmation')
@section('subheader', 'Your tutoring session has been scheduled and confirmed in Sandbox mode')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    
    <!-- Prominent Demo Mode Notice (Yellow Info Banner) -->
    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3.5 shadow-sm text-xs">
        <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg mt-0.5 shrink-0"></i>
        <p class="leading-relaxed">
            ⚠️ <strong>Demo Mode:</strong> This is a test payment system. No real charges were made to your account.
        </p>
    </div>

    <!-- Receipt Card -->
    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6 text-center">
        
        <!-- Animated Success Icon -->
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-3xl mx-auto shadow-sm">
            <i class="fa-solid fa-check"></i>
        </div>

        <div class="space-y-1">
            <h2 class="text-2xl font-extrabold font-heading text-slate-900">Booking Confirmed!</h2>
            <p class="text-xs text-slate-500">A confirmation receipt has been sent to {{ auth()->user()->email }}.</p>
        </div>

        <!-- Receipt Table -->
        <div class="p-5 rounded-xl bg-slate-50 border border-gray-100 text-left text-xs space-y-3">
            <div class="flex justify-between border-b border-gray-200/60 pb-2">
                <span class="text-slate-500">Booking Reference:</span>
                <span class="font-mono font-bold text-slate-900">#{{ $booking->booking_code }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-200/60 pb-2">
                <span class="text-slate-500">Tutor:</span>
                <span class="font-bold text-slate-900">{{ $booking->tutor->name }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-200/60 pb-2">
                <span class="text-slate-500">Subject:</span>
                <span class="font-semibold text-primary-800">{{ $booking->subject }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-200/60 pb-2">
                <span class="text-slate-500">Scheduled Date:</span>
                <span class="font-medium text-slate-800">{{ $booking->booking_date->format('l, M d, Y') }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-200/60 pb-2">
                <span class="text-slate-500">Scheduled Time:</span>
                <span class="font-medium text-slate-800">{{ date('g:i A', strtotime($booking->start_time)) }} - {{ date('g:i A', strtotime($booking->end_time)) }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-200/60 pb-2">
                <span class="text-slate-500">Payment Intent ID:</span>
                <span class="font-mono text-[11px] text-slate-600">{{ $payment?->stripe_payment_intent_id ?: 'pi_demo_test' }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-200/60 pb-2">
                <span class="text-slate-500">Payment Status:</span>
                <span class="font-bold text-emerald-700">Paid (Demo Sandbox)</span>
            </div>

            <div class="flex justify-between items-center pt-1 text-sm font-bold">
                <span class="text-slate-900 font-heading">Total Paid:</span>
                <span class="font-heading text-emerald-600">PKR {{ number_format($booking->total_amount, 0) }} (~${{ number_format($payment?->amount ?: ($booking->total_amount / 280), 2) }} USD)</span>
            </div>
        </div>

        <!-- Action CTAs -->
        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('student.bookings.index') }}" class="w-full sm:w-auto bg-primary-800 hover:bg-primary-900 text-white font-semibold text-xs px-6 py-3 rounded-xl shadow-md transition-all">
                Go to My Bookings
            </a>
            <a href="{{ route('student.messages.show', $booking->tutor_id) }}" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs px-5 py-3 rounded-xl transition-all">
                Message Tutor
            </a>
        </div>

    </div>

</div>
@endsection
