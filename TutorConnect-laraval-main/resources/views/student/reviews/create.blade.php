@extends('layouts.app')

@section('title', 'Rate & Review Session')
@section('header', 'Leave Tutor Review')
@section('subheader', 'Share your honest feedback and rating for your session with ' . $booking->tutor->name)

@section('content')
<div class="max-w-2xl" x-data="{ rating: {{ old('rating', 5) }}, hoverRating: 0 }">
    
    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
        
        <!-- Session Recap Card -->
        <div class="flex items-center gap-4 pb-6 border-b border-gray-100">
            <img src="{{ $booking->tutor->avatar_url }}" alt="{{ $booking->tutor->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-gray-100">
            <div>
                <h3 class="text-base font-bold font-heading text-slate-900">{{ $booking->tutor->name }}</h3>
                <p class="text-xs text-primary-800 font-semibold">{{ $booking->subject }} &bull; {{ $booking->booking_date->format('M d, Y') }}</p>
                <span class="inline-flex items-center gap-1 text-[11px] text-emerald-700 font-bold mt-1">
                    <i class="fa-solid fa-circle-check"></i> Completed Tutoring Session
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('student.reviews.store', $booking->id) }}" class="space-y-6">
            @csrf

            <!-- Interactive Alpine.js Star Rating (1 to 5) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Overall Rating (1 to 5 Stars)</label>
                
                <input type="hidden" name="rating" :value="rating" required>
                
                <div class="flex items-center gap-2">
                    <template x-for="i in 5" :key="i">
                        <button type="button" 
                                @click="rating = i" 
                                @mouseenter="hoverRating = i" 
                                @mouseleave="hoverRating = 0"
                                class="p-1 focus:outline-none transition-transform hover:scale-125">
                            <i class="fa-solid fa-star text-3xl"
                               :class="(hoverRating ? hoverRating >= i : rating >= i) ? 'text-amber-400' : 'text-slate-200'"></i>
                        </button>
                    </template>
                    <span class="ml-3 text-sm font-bold font-heading text-slate-900" x-text="rating + ' / 5.0'"></span>
                </div>
                @error('rating')
                    <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Comment Textarea (min 20 chars) -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Your Review & Feedback</label>
                    <span class="text-[11px] text-slate-400">Minimum 20 characters</span>
                </div>
                <textarea name="comment" rows="5" required minlength="20" placeholder="Describe your experience with this tutor. What teaching methods did they use? How did they help you prepare for exams or understand difficult concepts?..."
                          class="w-full text-xs sm:text-sm font-medium p-4 rounded-xl border @error('comment') border-rose-300 bg-rose-50/20 @else border-gray-200 bg-slate-50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800 leading-relaxed">{{ old('comment') }}</textarea>
                @error('comment')
                    <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('student.bookings.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs px-8 py-3 rounded-xl shadow-md shadow-amber-500/20 hover:shadow-lg transition-all flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Submit Verified Review</span>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
