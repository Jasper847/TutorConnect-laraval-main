@extends('layouts.dashboard')

@section('title', 'Book a Session with ' . $tutor->name)
@section('header', 'Book 1-on-1 Tutoring Session')
@section('subheader', 'Configure your session details, schedule, and learning topics')

@section('content')
<div class="max-w-4xl" x-data="{
    hourlyRate: {{ $tutor->tutorProfile->hourly_rate }},
    duration: 1.0,
    calculateTotal() {
        return (this.hourlyRate * parseFloat(this.duration)).toFixed(2);
    }
}">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Form -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                
                <!-- Tutor Header Card -->
                <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
                    <img src="{{ $tutor->avatar_url }}" alt="{{ $tutor->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">{{ $tutor->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $tutor->tutorProfile->headline }}</p>
                        <div class="flex items-center gap-2 mt-1 text-xs text-amber-500 font-bold">
                            <i class="fa-solid fa-star"></i>
                            <span class="text-slate-900">{{ number_format($tutor->tutorProfile->rating_cache, 1) }}</span>
                            <span class="text-slate-400 font-normal">({{ $tutor->tutorProfile->reviews_count }} reviews)</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('student.bookings.store', $tutor->id) }}" class="space-y-6">
                    @csrf

                    <!-- Subject Choice -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Subject for this Session</label>
                        <select name="subject_id" class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                            <option value="">General Tutoring / Consultation</option>
                            @foreach($tutor->tutorProfile->subjects as $subj)
                                <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date & Start Time -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Date</label>
                            <input type="date" name="booking_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}" required
                                   class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Start Time</label>
                            <input type="time" name="start_time" value="14:00" required
                                   class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                        </div>
                    </div>

                    <!-- Duration & Mode -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Session Duration</label>
                            <select name="duration_hours" x-model="duration" class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                                <option value="1.0">1 Hour (Standard)</option>
                                <option value="1.5">1.5 Hours (90 mins)</option>
                                <option value="2.0">2 Hours (Deep Dive)</option>
                                <option value="3.0">3 Hours (Workshop)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Session Mode</label>
                            <select name="mode" class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                                <option value="online">Online Video Session</option>
                                <option value="in_person">In-Person Tutoring</option>
                            </select>
                        </div>
                    </div>

                    <!-- Student Notes -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Topics / Notes for the Tutor (Optional)</label>
                        <textarea name="student_notes" rows="3" placeholder="Explain the specific exercises, chapters, or exam goals you want to focus on..."
                                  class="w-full text-sm font-medium px-4 py-2.5 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-accent-600 hover:bg-accent-700 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-accent-600/20 hover:shadow-xl transition-all flex items-center justify-center gap-2 text-sm">
                        <span>Proceed to Sandbox Checkout</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>

            </div>
        </div>

        <!-- Right Col: Price Summary Card -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-6 sticky top-28">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Order Summary</h3>

                <div class="space-y-3 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span>Tutor Rate</span>
                        <span class="font-bold text-slate-900">${{ number_format($tutor->tutorProfile->hourly_rate, 2) }} / hr</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Selected Duration</span>
                        <span class="font-bold text-slate-900" x-text="duration + ' hour(s)'"></span>
                    </div>
                    <div class="flex justify-between text-emerald-700 font-semibold">
                        <span>Platform Booking Fee</span>
                        <span>$0.00 (Free)</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-between items-baseline">
                    <span class="text-sm font-bold text-slate-900">Estimated Total</span>
                    <span class="text-2xl font-extrabold text-brand-800" x-text="'$' + calculateTotal()"></span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-500 space-y-2">
                    <p class="flex items-center gap-2 font-bold text-slate-700">
                        <i class="fa-solid fa-shield-halved text-accent-600"></i> Safe & Secure Booking
                    </p>
                    <p class="text-[11px] leading-relaxed">
                        Tutor will be notified immediately upon checkout. You can reschedule or cancel for a full refund up to 2 hours before the session.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
