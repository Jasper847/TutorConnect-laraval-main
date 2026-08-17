@extends('layouts.dashboard')

@section('title', 'Tutor Reviews & Feedback')
@section('header', 'Student Reviews & Ratings')
@section('subheader', 'Feedback left by students who completed tutoring sessions with you')

@section('content')
<div class="space-y-8 max-w-5xl">
    
    <!-- Rating Breakdown Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
        <!-- Average Score -->
        <div class="text-center md:border-r border-slate-100 md:pr-8 space-y-2">
            <span class="text-5xl font-extrabold text-slate-900">{{ number_format(auth()->user()->tutorProfile->rating_cache, 1) }}</span>
            <div class="flex justify-center text-amber-400 text-lg">
                @for($i=1; $i<=5; $i++)
                    <i class="fa-solid fa-star {{ $i <= round(auth()->user()->tutorProfile->rating_cache) ? 'text-amber-400' : 'text-slate-200' }}"></i>
                @endfor
            </div>
            <p class="text-xs text-slate-500 font-medium">Based on {{ $totalReviews }} verified student reviews</p>
        </div>

        <!-- Distribution Bars -->
        <div class="md:col-span-2 space-y-2.5">
            @for($star = 5; $star >= 1; $star--)
                @php
                    $count = $ratingDistribution[$star] ?? 0;
                    $percent = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                @endphp
                <div class="flex items-center gap-3 text-xs font-semibold text-slate-600">
                    <span class="w-12 text-right">{{ $star }} Star</span>
                    <div class="flex-1 h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full bg-amber-400 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                    <span class="w-8 text-right text-slate-400 font-medium">{{ $count }}</span>
                </div>
            @endfor
        </div>
    </div>

    <!-- Reviews Feed -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-4">All Reviews</h3>

        @if($reviews->isEmpty())
            <div class="text-center py-12 text-slate-400 space-y-2">
                <i class="fa-regular fa-star text-3xl text-slate-300"></i>
                <p class="text-xs">No reviews posted yet. Completed sessions will show up here once rated by students.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($reviews as $review)
                    <div class="border-b border-slate-100 pb-6 last:border-0 last:pb-0 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="{{ $review->student->avatar_url }}" alt="{{ $review->student->name }}" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">{{ $review->student->name }}</h4>
                                    <p class="text-[10px] text-slate-400">{{ $review->booking->subject?->name ?? 'Tutoring' }} — {{ $review->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center text-amber-400 text-xs">
                                @for($i=1; $i<=5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed italic pl-13">
                            "{{ $review->comment }}"
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="pt-4">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
