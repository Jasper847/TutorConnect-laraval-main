@extends('layouts.app')

@section('title', 'Sandbox Payment Checkout')
@section('header', 'Secure Demo Payment Checkout')
@section('subheader', 'Complete your booking confirmation in Stripe test mode')

@section('content')
<div class="max-w-4xl" x-data="{
    cardNumber: '4242 4242 4242 4242',
    expiry: '12/28',
    cvv: '123',
    cardholderName: '{{ auth()->user()->name }}',
    isSubmitting: false,

    fillTestCard() {
        this.cardNumber = '4242 4242 4242 4242';
        this.expiry = '12/28';
        this.cvv = '123';
    }
}">

    <!-- Prominent Demo Mode Notice (Yellow Info Banner) -->
    <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3.5 shadow-sm">
        <i class="fa-solid fa-triangle-exclamation text-amber-600 text-xl mt-0.5 shrink-0"></i>
        <div class="space-y-1 text-xs">
            <h4 class="font-bold font-heading text-amber-900 text-sm">⚠️ Demo Mode: Test Payment Environment</h4>
            <p class="leading-relaxed">
                This is a test payment system. No real charges will be made. Use test card: <strong class="font-mono bg-amber-100 px-1.5 py-0.5 rounded">4242 4242 4242 4242</strong>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Stripe Test Card Form -->
        <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-base font-bold font-heading text-slate-900">Credit or Debit Card (Stripe Test)</h3>
                    <p class="text-xs text-slate-500">Encrypted test payment gateway</p>
                </div>
                
                <button type="button" @click="fillTestCard()" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary-50 hover:bg-primary-100 text-primary-800 text-xs font-bold transition-colors">
                    <i class="fa-solid fa-wand-magic-sparkles text-[11px]"></i>
                    <span>Auto-Fill Test Card</span>
                </button>
            </div>

            <!-- Visual Test Card Info Box -->
            <div class="p-4 rounded-xl bg-slate-50 border border-gray-100 text-xs text-slate-600 space-y-2">
                <div class="flex items-center justify-between text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                    <span>Accepted Test Card Information</span>
                    <span class="text-emerald-600">Simulated Sandbox</span>
                </div>
                <div class="grid grid-cols-3 gap-2 font-mono text-slate-800 font-semibold pt-1">
                    <div>Card: <span class="text-slate-900">4242 4242...</span></div>
                    <div>Expiry: <span class="text-slate-900">Any future</span></div>
                    <div>CVV: <span class="text-slate-900">Any 3 digits</span></div>
                </div>
            </div>

            <form method="POST" action="{{ route('student.payment.process', $booking->id) }}" class="space-y-4" @submit="isSubmitting = true">
                @csrf

                <!-- Cardholder Name -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Cardholder Name</label>
                    <input type="text" name="cardholder_name" x-model="cardholderName" required
                           class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                </div>

                <!-- Card Number -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Card Number</label>
                    <div class="relative">
                        <i class="fa-regular fa-credit-card absolute left-3.5 top-3 text-slate-400"></i>
                        <input type="text" name="card_number" x-model="cardNumber" required placeholder="4242 4242 4242 4242"
                               class="w-full text-xs sm:text-sm font-mono font-medium pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                    </div>
                </div>

                <!-- Expiry & CVV -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Expiry Date (MM/YY)</label>
                        <input type="text" name="expiry" x-model="expiry" required placeholder="12/28"
                               class="w-full text-xs sm:text-sm font-mono font-medium px-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Security Code (CVV)</label>
                        <input type="text" name="cvv" x-model="cvv" required placeholder="123" maxlength="4"
                               class="w-full text-xs sm:text-sm font-mono font-medium px-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                    </div>
                </div>

                <!-- Pay Button & Cancel Link -->
                <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('student.payment.cancel', $booking->id) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                        Cancel & Return Later
                    </a>

                    <button type="submit" :disabled="isSubmitting" 
                            class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-semibold text-xs px-8 py-3.5 rounded-xl shadow-md shadow-emerald-600/20 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-lock text-[11px]"></i>
                        <span x-show="!isSubmitting">Pay ${{ number_format($amountUsd, 2) }} USD (Demo)</span>
                        <span x-show="isSubmitting" x-cloak>Processing Sandbox Charge...</span>
                    </button>
                </div>
            </form>

        </div>

        <!-- Right Col: Booking Summary & Price Conversion -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 pb-3">Session Breakdown</h3>

                <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                    <img src="{{ $booking->tutor->avatar_url }}" alt="{{ $booking->tutor->name }}" class="w-11 h-11 rounded-xl object-cover">
                    <div>
                        <h4 class="text-xs font-bold font-heading text-slate-900">{{ $booking->tutor->name }}</h4>
                        <p class="text-[11px] text-primary-800 font-semibold">{{ $booking->subject }}</p>
                    </div>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Booking Code:</span>
                        <span class="font-mono font-bold text-slate-900">#{{ $booking->booking_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Session Date:</span>
                        <span class="font-medium text-slate-800">{{ $booking->booking_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Session Time:</span>
                        <span class="font-medium text-slate-800">{{ date('g:i A', strtotime($booking->start_time)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Duration:</span>
                        <span class="font-medium text-slate-800">1.0 Hour (Online)</span>
                    </div>

                    <!-- PKR to USD conversion row -->
                    <div class="pt-3 border-t border-gray-100 space-y-1">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Tutor Rate:</span>
                            <span class="font-bold text-slate-800">PKR {{ number_format($amountPkr, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm pt-1">
                            <span class="font-bold font-heading text-slate-900">Total in USD:</span>
                            <span class="text-base font-extrabold font-heading text-emerald-600">
                                ${{ number_format($amountUsd, 2) }} USD
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-400 text-right">(Converted at ~PKR 280 / USD)</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
