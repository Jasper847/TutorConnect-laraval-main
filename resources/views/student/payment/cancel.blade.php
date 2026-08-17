@extends('layouts.app')

@section('title', 'Payment Cancelled')
@section('header', 'Payment Cancelled')
@section('subheader', 'The payment process was interrupted. Your booking remains pending.')

@section('content')
<div class="max-w-md mx-auto space-y-6 text-center">
    
    <!-- Yellow Demo Mode Banner -->
    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3.5 shadow-sm text-xs text-left">
        <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg mt-0.5 shrink-0"></i>
        <p class="leading-relaxed">
            ⚠️ <strong>Demo Mode:</strong> No charges were made. You can retry with test card <strong class="font-mono bg-amber-100 px-1 py-0.5 rounded">4242 4242 4242 4242</strong> anytime.
        </p>
    </div>

    <div class="bg-white p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
        <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center text-3xl mx-auto shadow-sm">
            <i class="fa-solid fa-xmark"></i>
        </div>

        <div class="space-y-2">
            <h3 class="text-xl font-bold font-heading text-slate-900">Payment Not Completed</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Your session with <strong>{{ $booking->tutor->name }}</strong> is currently saved as a <strong>Pending Request</strong>.
            </p>
        </div>

        <div class="pt-2 flex flex-col gap-2.5">
            <a href="{{ route('student.payment.checkout', $booking->id) }}" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-3 rounded-xl shadow-md transition-all">
                Try Payment Again (Demo)
            </a>
            <a href="{{ route('student.bookings.index') }}" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs py-3 rounded-xl transition-all">
                Return to My Bookings
            </a>
        </div>
    </div>

</div>
@endsection
