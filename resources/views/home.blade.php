@extends('layouts.guest')

@section('title', 'Find Your Perfect Tutor — 1-on-1 Online & In-Person Learning')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-primary-50/70 via-slate-50 to-slate-50 py-20 sm:py-28 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-3xl mx-auto space-y-6">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Verified Expert Mentors Available 24/7</span>
            </div>

            <!-- Headline -->
            <h1 class="text-4xl sm:text-6xl font-extrabold font-heading text-slate-900 tracking-tight leading-[1.15]">
                Find Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-800 to-emerald-600">Perfect Tutor</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                Connect with vetted academic specialists for Calculus, Physics, Coding, and IELTS. Personalized 1-on-1 tutoring tailored to your pace.
            </p>

            <!-- Search Bar -->
            <div class="pt-4 max-w-2xl mx-auto">
                <form action="{{ route('tutors.index') }}" method="GET" class="bg-white p-2.5 rounded-2xl shadow-lg border border-gray-100 flex flex-col sm:flex-row items-center gap-2.5">
                    <div class="flex-1 flex items-center gap-3 px-3.5 w-full">
                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                        <input type="text" name="q" placeholder="What subject do you want to learn? (e.g. Math, Python, IELTS)" 
                               class="w-full text-xs sm:text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none bg-transparent">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-primary-800 hover:bg-primary-900 text-white text-xs font-semibold px-7 py-3.5 rounded-xl shadow-md shadow-primary-800/20 hover:shadow-lg transition-all flex items-center justify-center gap-2 shrink-0">
                        <span>Search Tutors</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </form>
            </div>

            <!-- Stats Row -->
            <div class="pt-10 grid grid-cols-3 gap-4 max-w-2xl mx-auto border-t border-gray-200/60 text-center">
                <div class="space-y-0.5">
                    <h3 class="text-2xl sm:text-3xl font-extrabold font-heading text-primary-800">500+</h3>
                    <p class="text-xs font-semibold text-slate-500">Verified Tutors</p>
                </div>
                <div class="space-y-0.5 border-x border-gray-200">
                    <h3 class="text-2xl sm:text-3xl font-extrabold font-heading text-emerald-600">10,000+</h3>
                    <p class="text-xs font-semibold text-slate-500">Active Students</p>
                </div>
                <div class="space-y-0.5">
                    <h3 class="text-2xl sm:text-3xl font-extrabold font-heading text-primary-800">50+</h3>
                    <p class="text-xs font-semibold text-slate-500">Academic Subjects</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section (3 steps: Search -> Book -> Learn) -->
<section id="how-it-works" class="py-20 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Simple 3-Step Process</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold font-heading text-slate-900">How TutorConnect Works</h2>
            <p class="text-sm text-slate-600">Get matched with your ideal tutor and begin mastering your coursework in minutes.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1: Search -->
            <div class="bg-slate-50 p-8 rounded-xl border border-gray-100 text-center space-y-4 hover:shadow-md transition-shadow">
                <div class="w-14 h-14 mx-auto rounded-xl bg-primary-800 text-white flex items-center justify-center text-xl shadow-md shadow-primary-800/20">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h3 class="text-lg font-bold font-heading text-slate-900">1. Search & Filter</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Browse verified tutor profiles filtered by subject, student reviews, teaching experience, and budget.
                </p>
            </div>

            <!-- Step 2: Book -->
            <div class="bg-slate-50 p-8 rounded-xl border border-gray-100 text-center space-y-4 hover:shadow-md transition-shadow">
                <div class="w-14 h-14 mx-auto rounded-xl bg-emerald-600 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-600/20">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
                <h3 class="text-lg font-bold font-heading text-slate-900">2. Book Your Session</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Select your preferred date, time slot, and session format (online video or in-person) with instant scheduling.
                </p>
            </div>

            <!-- Step 3: Learn -->
            <div class="bg-slate-50 p-8 rounded-xl border border-gray-100 text-center space-y-4 hover:shadow-md transition-shadow">
                <div class="w-14 h-14 mx-auto rounded-xl bg-primary-800 text-white flex items-center justify-center text-xl shadow-md shadow-primary-800/20">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h3 class="text-lg font-bold font-heading text-slate-900">3. Learn & Excel</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Attend 1-on-1 personalized sessions, access study materials, and track your continuous academic progress.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Tutors Section (Show 6 tutor cards) -->
<section class="py-20 bg-slate-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-12">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Top-Rated Educators</span>
                <h2 class="text-3xl font-extrabold font-heading text-slate-900 mt-1">Featured Tutors</h2>
            </div>
            <a href="{{ route('tutors.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-primary-800 hover:text-primary-900 group">
                <span>View all verified tutors</span>
                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredTutors->take(6) as $tutorProfile)
                <x-tutor-card :tutor="$tutorProfile" />
            @endforeach
        </div>
    </div>
</section>

<!-- Popular Subjects Section (Math, Physics, English, Chemistry, CS, Biology) -->
<section class="py-20 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Academic Catalog</span>
            <h2 class="text-3xl font-extrabold font-heading text-slate-900">Explore Subjects</h2>
            <p class="text-sm text-slate-600">Choose from top academic disciplines taught by expert educators.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6">
            @php
                $topSubjects = [
                    ['name' => 'Mathematics', 'icon' => 'fa-solid fa-calculator', 'color' => 'text-blue-600', 'slug' => 'mathematics'],
                    ['name' => 'Physics', 'icon' => 'fa-solid fa-atom', 'color' => 'text-purple-600', 'slug' => 'physics'],
                    ['name' => 'English', 'icon' => 'fa-solid fa-book-open', 'color' => 'text-emerald-600', 'slug' => 'english'],
                    ['name' => 'Chemistry', 'icon' => 'fa-solid fa-flask-vial', 'color' => 'text-amber-600', 'slug' => 'chemistry'],
                    ['name' => 'Computer Science', 'icon' => 'fa-solid fa-laptop-code', 'color' => 'text-primary-800', 'slug' => 'computer-science'],
                    ['name' => 'Biology', 'icon' => 'fa-solid fa-dna', 'color' => 'text-rose-600', 'slug' => 'biology'],
                ];
            @endphp

            @foreach($topSubjects as $subj)
                <a href="{{ route('tutors.index', ['subject' => $subj['slug']]) }}" 
                   class="bg-slate-50 hover:bg-white p-5 rounded-xl border border-gray-100 hover:border-primary-800/30 hover:shadow-md transition-all text-center flex flex-col items-center justify-center space-y-3 group">
                    <div class="w-12 h-12 rounded-xl bg-white group-hover:bg-primary-800 group-hover:text-white {{ $subj['color'] }} flex items-center justify-center text-xl shadow-sm transition-colors">
                        <i class="{{ $subj['icon'] }}"></i>
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold font-heading text-slate-900 group-hover:text-primary-800 transition-colors">
                        {{ $subj['name'] }}
                    </h3>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Testimonials Section (3 quotes) -->
<section class="py-20 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Student Reviews</span>
            <h2 class="text-3xl font-extrabold font-heading">What Our Students Say</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-800/70 border border-slate-700 p-6 rounded-xl space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex text-amber-400 text-xs">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-slate-300 italic leading-relaxed">
                        "Ahmed Khan helped me understand Calculus integration steps with such clarity. I improved my A-Levels exam grade from a C to an A* in just 2 months!"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-3 border-t border-slate-700">
                    <div class="w-8 h-8 rounded-full bg-primary-800 flex items-center justify-center text-xs font-bold">AR</div>
                    <div>
                        <h4 class="text-xs font-bold">Ali Raza</h4>
                        <p class="text-[10px] text-slate-400">A-Levels Student</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/70 border border-slate-700 p-6 rounded-xl space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex text-amber-400 text-xs">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-slate-300 italic leading-relaxed">
                        "Dr. Sarah made Organic Chemistry reaction mechanisms simple. Her practice problem worksheets were directly aligned with MDCAT entry tests."
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-3 border-t border-slate-700">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-bold">OT</div>
                    <div>
                        <h4 class="text-xs font-bold">Omer Tariq</h4>
                        <p class="text-[10px] text-slate-400">Pre-Medical Student</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/70 border border-slate-700 p-6 rounded-xl space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex text-amber-400 text-xs">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-slate-300 italic leading-relaxed">
                        "Fatima Noor is the best IELTS mentor! Her feedback on my essays and mock speaking drills helped me achieve an overall 8.0 band."
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-3 border-t border-slate-700">
                    <div class="w-8 h-8 rounded-full bg-primary-800 flex items-center justify-center text-xs font-bold">HS</div>
                    <div>
                        <h4 class="text-xs font-bold">Hamza Sheikh</h4>
                        <p class="text-[10px] text-slate-400">IELTS Aspirant</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Banner ("Ready to Start Learning?") -->
<section class="py-16 bg-gradient-to-r from-primary-800 to-emerald-700 text-white">
    <div class="max-w-5xl mx-auto px-4 text-center space-y-6">
        <h2 class="text-3xl sm:text-4xl font-extrabold font-heading">Ready to Start Learning?</h2>
        <p class="text-sm sm:text-base text-slate-100 max-w-2xl mx-auto leading-relaxed">
            Join thousands of ambitious students and expert educators on TutorConnect today. Free registration with zero platform booking fees.
        </p>
        <div class="flex flex-wrap justify-center gap-4 pt-2">
            <a href="{{ route('register') }}?role=student" class="bg-white text-primary-800 hover:bg-slate-50 font-bold text-xs px-8 py-3.5 rounded-xl shadow-lg transition-all">
                Register as a Student
            </a>
            <a href="{{ route('register') }}?role=tutor" class="bg-primary-900/50 hover:bg-primary-900/80 text-white font-bold text-xs px-8 py-3.5 rounded-xl border border-white/30 transition-all">
                Apply as a Tutor
            </a>
        </div>
    </div>
</section>
@endsection