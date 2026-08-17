@extends('layouts.app')

@section('title', 'Find Tutors - Search by Subject, Rating & Price')

@section('content')
<div class="py-10 sm:py-14 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Find Your Ideal Tutor</h1>
            <p class="text-sm text-slate-600 mt-1">Browse {{ $tutors->total() }} expert educators ready to help you succeed.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            
            <!-- Filters Sidebar -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm sticky top-28 space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-brand-800"></i> Filters
                    </h3>
                    @if(request()->hasAny(['q', 'subject', 'min_price', 'max_price', 'mode', 'rating', 'verified_only', 'sort']))
                        <a href="{{ route('tutors.index') }}" class="text-xs font-semibold text-rose-600 hover:underline">Reset All</a>
                    @endif
                </div>

                <form action="{{ route('tutors.index') }}" method="GET" class="space-y-5">
                    <!-- Keyword Search -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Search Keyword</label>
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Name, topic, or keyword" 
                                   class="w-full text-xs font-medium pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 bg-slate-50">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
                        </div>
                    </div>

                    <!-- Subject Filter -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Subject</label>
                        <select name="subject" class="w-full text-xs font-medium px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 bg-slate-50">
                            <option value="">All Subjects</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->slug }}" {{ request('subject') == $subj->slug ? 'selected' : '' }}>
                                    {{ $subj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Hourly Rate ($)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min $" min="0" 
                                   class="w-full text-xs font-medium px-3 py-2 rounded-xl border border-slate-200 focus:outline-none bg-slate-50">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max $" min="0" 
                                   class="w-full text-xs font-medium px-3 py-2 rounded-xl border border-slate-200 focus:outline-none bg-slate-50">
                        </div>
                    </div>

                    <!-- Teaching Mode -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Teaching Mode</label>
                        <select name="mode" class="w-full text-xs font-medium px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none bg-slate-50">
                            <option value="all">All Modes</option>
                            <option value="online" {{ request('mode') == 'online' ? 'selected' : '' }}>Online Sessions</option>
                            <option value="in_person" {{ request('mode') == 'in_person' ? 'selected' : '' }}>In-Person Sessions</option>
                        </select>
                    </div>

                    <!-- Minimum Rating -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Minimum Rating</label>
                        <select name="rating" class="w-full text-xs font-medium px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none bg-slate-50">
                            <option value="">Any Rating</option>
                            <option value="4.5" {{ request('rating') == '4.5' ? 'selected' : '' }}>⭐ 4.5 & Above</option>
                            <option value="4.0" {{ request('rating') == '4.0' ? 'selected' : '' }}>⭐ 4.0 & Above</option>
                            <option value="3.0" {{ request('rating') == '3.0' ? 'selected' : '' }}>⭐ 3.0 & Above</option>
                        </select>
                    </div>

                    <!-- Verified Badge Only -->
                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="verified_only" value="1" {{ request('verified_only') ? 'checked' : '' }} 
                                   class="rounded text-brand-800 focus:ring-brand-800 h-4 w-4">
                            <span class="text-xs font-semibold text-slate-700">Verified Tutors Only</span>
                        </label>
                    </div>

                    <!-- Sorting -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Sort Results</label>
                        <select name="sort" class="w-full text-xs font-medium px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none bg-slate-50">
                            <option value="recommended" {{ request('sort') == 'recommended' ? 'selected' : '' }}>Recommended</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rating</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="experience" {{ request('sort') == 'experience' ? 'selected' : '' }}>Most Experienced</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-brand-800 hover:bg-brand-900 text-white font-bold text-xs py-3 rounded-xl shadow-md transition-all">
                        Apply Filters
                    </button>
                </form>
            </div>

            <!-- Tutors List -->
            <div class="lg:col-span-3 space-y-6">
                @if($tutors->isEmpty())
                    <div class="bg-white p-12 rounded-3xl border border-slate-200/80 text-center space-y-4 shadow-sm">
                        <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-2xl text-slate-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">No tutors found matching your criteria</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">Try loosening your search filters or searching for another subject category.</p>
                        <a href="{{ route('tutors.index') }}" class="inline-block bg-brand-800 text-white text-xs font-bold px-6 py-2.5 rounded-xl">
                            Clear Filters
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($tutors as $tutorProfile)
                            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm hover:shadow-xl hover:shadow-slate-200/60 transition-all flex flex-col justify-between group">
                                <div>
                                    <!-- Header & Avatar -->
                                    <div class="flex items-start gap-4 mb-4">
                                        <div class="relative shrink-0">
                                            <img src="{{ $tutorProfile->user->avatar_url }}" alt="{{ $tutorProfile->user->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100">
                                            @if($tutorProfile->is_verified)
                                                <span class="absolute -bottom-1 -right-1 bg-accent-600 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px] ring-2 ring-white" title="Verified Tutor">
                                                    <i class="fa-solid fa-check"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-800 transition-colors truncate">
                                                {{ $tutorProfile->user->name }}
                                            </h3>
                                            <p class="text-xs text-slate-500 truncate">{{ $tutorProfile->qualification }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <div class="flex items-center text-amber-400 text-xs">
                                                    <i class="fa-solid fa-star"></i>
                                                    <span class="ml-1 font-bold text-slate-900">{{ number_format($tutorProfile->rating_cache, 1) }}</span>
                                                </div>
                                                <span class="text-xs text-slate-400">({{ $tutorProfile->reviews_count }})</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Headline & Bio snippet -->
                                    <p class="text-xs font-semibold text-brand-800 line-clamp-1 mb-2">{{ $tutorProfile->headline }}</p>
                                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-3 mb-4">{{ $tutorProfile->bio }}</p>

                                    <!-- Meta Badges -->
                                    <div class="flex items-center gap-3 text-[11px] font-semibold text-slate-500 mb-4">
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-briefcase text-slate-400"></i> {{ $tutorProfile->experience_years }} yrs exp</span>
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot text-slate-400"></i> {{ $tutorProfile->user->city ?: 'Online' }}</span>
                                    </div>

                                    <!-- Subjects Tags -->
                                    <div class="flex flex-wrap gap-1.5 mb-5">
                                        @foreach($tutorProfile->subjects->take(3) as $subj)
                                            <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700">
                                                {{ $subj->name }}
                                            </span>
                                        @endforeach
                                        @if($tutorProfile->subjects->count() > 3)
                                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-50 text-slate-400">+{{ $tutorProfile->subjects->count() - 3 }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Footer Details & Action -->
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-slate-400">Hourly Rate</span>
                                        <p class="text-base font-extrabold text-slate-900">${{ number_format($tutorProfile->hourly_rate, 2) }}<span class="text-[10px] font-normal text-slate-500">/hr</span></p>
                                    </div>
                                    <a href="{{ route('tutors.show', $tutorProfile->user_id) }}" class="inline-flex items-center gap-1.5 bg-brand-800 hover:bg-brand-900 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-sm">
                                        <span>View Profile</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pt-6">
                        {{ $tutors->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
