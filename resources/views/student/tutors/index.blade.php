@extends('layouts.app')

@section('title', 'Find Verified Tutors')
@section('header', 'Find Expert Tutors')
@section('subheader', 'Search and filter vetted mentors by subject, hourly rate, and weekly availability')

@section('content')
<div class="space-y-6">
    
    <!-- Comprehensive Filter Bar -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('student.tutors.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <!-- Keyword Search -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Keyword / Topic</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name or subject..."
                           class="w-full text-xs font-medium pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20">
                </div>
            </div>

            <!-- Subject Filter (Dropdown) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Subject</label>
                <select name="subject" class="w-full text-xs font-medium px-3 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj }}" {{ request('subject') == $subj ? 'selected' : '' }}>{{ $subj }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Availability Day Filter (Dropdown) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Available Day</label>
                <select name="day" class="w-full text-xs font-medium px-3 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20">
                    <option value="">Any Day</option>
                    @foreach($days as $day)
                        <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Price Range (Min/Max PKR) -->
            <div class="flex items-center gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Min Rate</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min PKR" min="500" step="100"
                           class="w-full text-xs font-medium px-3 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Max Rate</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max PKR" max="10000" step="100"
                           class="w-full text-xs font-medium px-3 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none">
                </div>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-primary-800 hover:bg-primary-900 text-white font-semibold text-xs py-2.5 rounded-xl shadow-sm transition-all text-center flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-filter text-xs"></i> Filter
                </button>
                <a href="{{ route('student.tutors.index') }}" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Results Header -->
    <div class="flex items-center justify-between">
        <p class="text-xs font-semibold text-slate-500">
            Showing <span class="text-slate-900 font-bold">{{ $tutors->total() }}</span> verified tutors
        </p>
    </div>

    <!-- Tutor Cards Grid (Paginated 9 per page) -->
    @if($tutors->isEmpty())
        <div class="bg-white p-12 rounded-xl border border-gray-100 text-center space-y-3 shadow-sm">
            <i class="fa-solid fa-user-xmark text-4xl text-slate-300"></i>
            <h3 class="text-base font-bold font-heading text-slate-900">No tutors found</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Try broadening your search or resetting the filters.</p>
            <a href="{{ route('student.tutors.index') }}" class="inline-block px-4 py-2 rounded-xl text-xs font-semibold bg-primary-800 text-white">Reset Filters</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tutors as $tutorProfile)
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <!-- Tutor Header -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="relative shrink-0">
                                <img src="{{ $tutorProfile->user->avatar_url }}" alt="{{ $tutorProfile->user->name }}" class="w-14 h-14 rounded-xl object-cover ring-2 ring-gray-100">
                                @if($tutorProfile->is_verified)
                                    <span class="absolute -bottom-1 -right-1 bg-emerald-600 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px] ring-2 ring-white" title="Verified Tutor">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold font-heading text-slate-900 group-hover:text-primary-800 transition-colors truncate">
                                    {{ $tutorProfile->user->name }}
                                </h3>
                                <p class="text-xs text-slate-500 truncate">{{ $tutorProfile->education ?: $tutorProfile->qualification }}</p>
                                
                                <!-- Rating -->
                                <div class="flex items-center gap-1.5 mt-1">
                                    <div class="flex items-center text-amber-400 text-xs">
                                        <i class="fa-solid fa-star"></i>
                                        <span class="ml-1 font-bold text-slate-900">{{ number_format($tutorProfile->avg_rating ?? ($tutorProfile->rating_cache ?? 5.0), 1) }}</span>
                                    </div>
                                    <span class="text-xs text-slate-400">({{ $tutorProfile->reviews_count ?? 0 }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Headline & Bio -->
                        <p class="text-xs font-semibold text-primary-800 line-clamp-1 mb-1.5">{{ $tutorProfile->headline }}</p>
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 mb-4">{{ $tutorProfile->bio }}</p>

                        <!-- Subjects Tags -->
                        @php
                            $subjs = is_array($tutorProfile->subjects) ? $tutorProfile->subjects : ($tutorProfile->subjects ? json_decode($tutorProfile->subjects, true) : []);
                        @endphp
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            @foreach(array_slice($subjs, 0, 3) as $s)
                                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 text-slate-700">
                                    {{ is_array($s) ? ($s['name'] ?? '') : $s }}
                                </span>
                            @endforeach
                            @if(count($subjs) > 3)
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-50 text-slate-400">+{{ count($subjs) - 3 }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Hourly Rate & Actions -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400">Hourly Rate</span>
                            <p class="text-base font-bold font-heading text-slate-900">
                                PKR {{ number_format($tutorProfile->hourly_rate, 0) }}<span class="text-[10px] font-normal text-slate-500">/hr</span>
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('student.tutors.show', $tutorProfile->user_id) }}" class="inline-flex items-center gap-1 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold px-3 py-2 rounded-xl transition-colors">
                                View Profile
                            </a>
                            <a href="{{ route('student.bookings.create', $tutorProfile->user_id) }}" class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl shadow-sm transition-all">
                                Book
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $tutors->links() }}
        </div>
    @endif

</div>
@endsection
