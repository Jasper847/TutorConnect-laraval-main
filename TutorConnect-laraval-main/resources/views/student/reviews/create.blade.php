@extends('layouts.dashboard')

@section('title', 'Leave a Review for ' . $booking->tutor->name)
@section('header', 'Rate & Review Your Session')
@section('subheader', 'Share your learning experience with other students')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ rating: 5 }">
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
        
        <!-- Tutor Card -->
        <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
            <img src="{{ $booking->tutor->avatar_url }}" alt="{{ $booking->tutor->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">{{ $booking->tutor->name }}</h3>
                <p class="text-xs text-slate-500">{{ $booking->subject?->name ?? 'Tutoring Session' }} — {{ $booking->booking_date->format('M d, Y') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('student.reviews.store', $booking->id) }}" class="space-y-6">
            @csrf

            <!-- Interactive Star Rating -->
            <div class="text-center py-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">Overall Rating</label>
                <div class="flex justify-center gap-2 text-3xl">
                    <template x-for="star in 5">
                        <button type="button" @click="rating = star" class="focus:outline-none transition-transform hover:scale-125">
                            <i class="fa-solid fa-star transition-colors" :class="star <= rating ? 'text-amber-400' : 'text-slate-300'"></i>
                        </button>
                    </template>
                </div>
                <input type="hidden" name="rating" :value="rating">
                <p class="text-xs font-bold text-slate-700" x-text="rating + ' out of 5 Stars'"></p>
            </div>

            <!-- Comment Box -->
            <div>
                <label for="comment" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Your Feedback & Review</label>
                <textarea id="comment" name="comment" rows="4" required minlength="5"
                          placeholder="How did the tutor explain concepts? Was the pace helpful? Did you solve your exam doubts?..."
                          class="w-full text-sm font-medium p-4 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all"></textarea>
                @error('comment')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-brand-800 hover:bg-brand-900 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-brand-800/20 hover:shadow-xl transition-all text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Submit Verified Review</span>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
