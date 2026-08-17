@extends('layouts.guest')

@section('title', 'About Us — Our Mission & Vision')

@section('content')
<div class="py-16 sm:py-24 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        <!-- Header -->
        <div class="text-center space-y-4 max-w-3xl mx-auto">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Empowering Learners</span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900">Democratizing Quality 1-on-1 Education Everywhere</h1>
            <p class="text-lg text-slate-600 font-medium">TutorConnect is a modern educational marketplace bridging passionate tutors with ambitious learners.</p>
        </div>

        <!-- Vision / Mission Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-800 flex items-center justify-center text-2xl font-bold">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h3 class="text-xl font-bold font-heading text-slate-900">Our Mission</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    To make personalized tutoring transparent, accessible, and reliably vetted. Every student deserves a dedicated mentor who tailors teaching to their specific learning style.
                </p>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-xl font-bold font-heading text-slate-900">Verified Quality & Safety</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Every tutor on TutorConnect undergoes administrative verification of educational credentials, identity, and teaching background before receiving a verified badge.
                </p>
            </div>
        </div>

        <!-- Platform Values -->
        <div class="bg-white p-10 rounded-3xl border border-gray-100 shadow-sm space-y-10">
            <h2 class="text-2xl font-bold font-heading text-slate-900 text-center">Why Students & Tutors Trust TutorConnect</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="space-y-2">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-primary-800 text-white flex items-center justify-center text-xl shadow-sm">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <h4 class="text-base font-bold font-heading text-slate-900">Transparent Pricing</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Tutors set their own hourly rates with zero hidden student booking surcharges.</p>
                </div>

                <div class="space-y-2">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl shadow-sm">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <h4 class="text-base font-bold font-heading text-slate-900">Direct In-App Messaging</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Communicate session goals, share study materials, and confirm slot logistics in real time.</p>
                </div>

                <div class="space-y-2">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-primary-800 text-white flex items-center justify-center text-xl shadow-sm">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h4 class="text-base font-bold font-heading text-slate-900">Honest Verified Reviews</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Reviews can only be posted by students who have genuinely completed paid tutoring sessions.</p>
                </div>
            </div>
        </div>

        <!-- Stripe Sandbox Note for Demonstration -->
        <div class="bg-slate-900 text-white p-8 rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-6 shadow-md">
            <div class="space-y-1 text-center sm:text-left">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                    <i class="fa-solid fa-flask"></i> Sandbox Demo Mode
                </span>
                <h3 class="text-lg font-bold font-heading text-white pt-1">Stripe Test Mode Enabled</h3>
                <p class="text-xs text-slate-400">All payment transactions are conducted in sandbox demonstration mode. No real credit card charges occur.</p>
            </div>
            <a href="{{ route('tutors.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-6 py-3 rounded-xl shrink-0 transition-all shadow-md">
                Browse Marketplace
            </a>
        </div>
    </div>
</div>
@endsection