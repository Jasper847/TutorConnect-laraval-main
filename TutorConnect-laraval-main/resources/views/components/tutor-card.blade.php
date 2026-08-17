@props([
    'tutor' => null, // Expects TutorProfile or User model
])

@php
    $profile = $tutor instanceof \App\Models\TutorProfile ? $tutor : ($tutor->tutorProfile ?? null);
    $user = $tutor instanceof \App\Models\User ? $tutor : ($profile->user ?? null);
    $subjects = is_array($profile?->subjects) ? $profile->subjects : ($profile?->subjects ? json_decode($profile->subjects, true) : []);
@endphp

@if($user && $profile)
<div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
    <div>
        <!-- Tutor Header -->
        <div class="flex items-start gap-4 mb-4">
            <div class="relative shrink-0">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-xl object-cover ring-2 ring-gray-100">
                @if($profile->is_verified)
                    <span class="absolute -bottom-1 -right-1 bg-emerald-600 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px] ring-2 ring-white shadow-xs" title="Verified Tutor">
                        <i class="fa-solid fa-check"></i>
                    </span>
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <h3 class="text-sm font-bold font-heading text-slate-900 group-hover:text-primary-800 transition-colors truncate">
                    {{ $user->name }}
                </h3>
                <p class="text-xs text-slate-500 truncate">{{ $profile->education ?: $profile->qualification }}</p>
                
                <!-- Golden Yellow Star Rating -->
                <div class="flex items-center gap-1.5 mt-1">
                    <div class="flex items-center text-yellow-400 text-xs">
                        <i class="fa-solid fa-star"></i>
                        <span class="ml-1 font-bold text-slate-900">{{ number_format($profile->avg_rating ?? ($profile->rating_cache ?? 5.0), 1) }}</span>
                    </div>
                    <span class="text-xs text-slate-400">({{ $profile->reviews_count ?? 0 }})</span>
                </div>
            </div>
        </div>

        <!-- Headline & Bio -->
        <p class="text-xs font-semibold text-primary-800 line-clamp-1 mb-1.5">{{ $profile->headline }}</p>
        <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 mb-4">{{ $profile->bio }}</p>

        <!-- Subjects Badges -->
        <div class="flex flex-wrap gap-1.5 mb-5">
            @foreach(array_slice($subjects, 0, 3) as $subj)
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 text-slate-700">
                    {{ is_array($subj) ? ($subj['name'] ?? '') : $subj }}
                </span>
            @endforeach
            @if(count($subjects) > 3)
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-50 text-slate-400">+{{ count($subjects) - 3 }}</span>
            @endif
        </div>
    </div>

    <!-- Footer Pricing & Book Now -->
    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
        <div>
            <span class="text-[10px] uppercase font-bold text-slate-400">Hourly Rate</span>
            <p class="text-base font-bold font-heading text-slate-900">
                PKR {{ number_format($profile->hourly_rate, 0) }}<span class="text-[10px] font-normal text-slate-500">/hr</span>
            </p>
        </div>
        <a href="{{ route('tutors.show', $user->id) }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm hover:shadow transition-all duration-200">
            <span>Book Now</span>
            <i class="fa-solid fa-chevron-right text-[9px]"></i>
        </a>
    </div>
</div>
@endif
