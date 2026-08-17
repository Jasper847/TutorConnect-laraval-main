@extends('layouts.dashboard')

@section('title', 'Sandbox Payment Checkout')
@section('header', 'Stripe Checkout (Sandbox Demo Mode)')
@section('subheader', 'Complete your booking confirmation in test mode without real charges')

@section('content')
<div class="max-w-4xl" x-data="{
    fillTestCard() {
        document.getElementById('card_number').value = '4242 4242 4242 4242';
        document.getElementById('expiry').value = '12/28';
        document.getElementById('cvv').value = '888';
        document.getElementById('cardholder').value = '{{ auth()->user()->name }}';
    }
}">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Sandbox Checkout Card -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                
                <!-- Sandbox Banner -->
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3.5">
                    <i class="fa-solid fa-flask text-amber-600 text-lg mt-0.5"></i>
                    <div class="text-xs text-amber-800 space-y-1">
                        <p class="font-bold">Sandbox / Test Mode Active</p>
                        <p class="leading-relaxed">
                            Because live Stripe merchant payments are not available in Pakistan, this platform operates in full Sandbox Demo Mode. No real credit card charges will be made.
                        </p>
                    </div>
                </div>

                <!-- Test Card Autofill Helper -->
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-200">
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-stripe text-brand-800 text-2xl"></i>
                        <div>
                            <p class="text-xs font-bold text-slate-900">Stripe Test Card Helper</p>
                            <p class="text-[11px] text-slate-500">Auto-fill mock card number</p>
                        </div>
                    </div>
                    <button type="button" @click="fillTestCard()" class="bg-brand-800 hover:bg-brand-900 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm transition-all">
                        Auto-Fill Test Card
                    </button>
                </div>

                <!-- Payment Form -->
                <form method="POST" action="{{ route('student.payment.process', $booking->id) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Cardholder Name</label>
                        <input id="cardholder" type="text" value="{{ auth()->user()->name }}" required
                               class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Card Number</label>
                        <div class="relative">
                            <input id="card_number" type="text" name="card_number" placeholder="4242 •••• •••• 4242" required
                                   class="w-full text-sm font-mono font-medium pl-10 pr-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                            <i class="fa-regular fa-credit-card absolute left-3.5 top-3.5 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Expiry Date</label>
                            <input id="expiry" type="text" name="expiry" placeholder="MM/YY" required
                                   class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">CVC / CVV</label>
                            <input id="cvv" type="text" name="cvv" placeholder="123" required
                                   class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-accent-600 hover:bg-accent-700 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-accent-600/20 hover:shadow-xl transition-all flex items-center justify-center gap-2 text-sm">
                            <i class="fa-solid fa-lock"></i>
                            <span>Confirm Sandbox Payment (${{ number_format($booking->total_amount, 2) }})</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- Right Col: Booking Summary -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Booking Summary</h3>

                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <img src="{{ $booking->tutor->avatar_url }}" alt="{{ $booking->tutor->name }}" class="w-12 h-12 rounded-xl object-cover">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">{{ $booking->tutor->name }}</h4>
                        <p class="text-xs text-brand-800 font-semibold">{{ $booking->subject?->name ?? 'Tutoring Session' }}</p>
                    </div>
                </div>

                <div class="space-y-2.5 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span>Date:</span>
                        <span class="font-bold text-slate-900">{{ $booking->booking_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Time:</span>
                        <span class="font-bold text-slate-900">{{ date('g:i A', strtotime($booking->start_time)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Mode:</span>
                        <span class="font-bold text-slate-900 uppercase">{{ $booking->mode }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-between items-baseline">
                    <span class="text-sm font-bold text-slate-900">Total Payable</span>
                    <span class="text-2xl font-extrabold text-brand-800">${{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
