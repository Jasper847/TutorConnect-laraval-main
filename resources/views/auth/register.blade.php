@extends('layouts.guest')

@section('title', 'Create Account')

@section('content')
<div x-data="{ role: '{{ old('role', $selectedRole ?? 'student') }}' }">
    <!-- Header -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-extrabold text-slate-900">Join TutorConnect</h2>
        <p class="text-xs text-slate-500 mt-1">Create your free account in seconds</p>
    </div>

    <!-- Role Switcher Tabs -->
    <div class="mb-6 p-1.5 rounded-2xl bg-slate-100 grid grid-cols-2 gap-1">
        <button type="button" @click="role = 'student'" 
                :class="role === 'student' ? 'bg-white text-brand-800 shadow-sm font-bold' : 'text-slate-500 font-semibold hover:text-slate-800'"
                class="py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition-all">
            <i class="fa-solid fa-user-graduate"></i>
            <span>I want to Learn (Student)</span>
        </button>
        <button type="button" @click="role = 'tutor'" 
                :class="role === 'tutor' ? 'bg-white text-brand-800 shadow-sm font-bold' : 'text-slate-500 font-semibold hover:text-slate-800'"
                class="py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition-all">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span>I want to Teach (Tutor)</span>
        </button>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="role" :value="role">

        <!-- Full Name -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                   placeholder="e.g. John Doe"
                   class="w-full text-sm font-medium px-4 py-3 rounded-2xl border @error('name') border-rose-400 bg-rose-50/30 @else border-slate-200 bg-slate-50/50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
            @error('name')
                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   placeholder="john@example.com"
                   class="w-full text-sm font-medium px-4 py-3 rounded-2xl border @error('email') border-rose-400 bg-rose-50/30 @else border-slate-200 bg-slate-50/50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
            @error('email')
                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- City & Phone -->
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="city" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">City</label>
                <input id="city" type="text" name="city" value="{{ old('city') }}" placeholder="e.g. Lahore"
                       class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
            </div>
            <div>
                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Phone (Optional)</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+92 300..."
                       class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
            </div>
        </div>

        <!-- Dynamic Fields for Tutor -->
        <template x-if="role === 'tutor'">
            <div class="space-y-4 pt-2 border-t border-slate-100">
                <div>
                    <label for="headline" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Professional Headline</label>
                    <input id="headline" type="text" name="headline" value="{{ old('headline') }}" placeholder="e.g. High School Calculus & Physics Specialist"
                           class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="hourly_rate" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Hourly Rate ($)</label>
                        <input id="hourly_rate" type="number" name="hourly_rate" value="{{ old('hourly_rate', 25) }}" min="5" max="500" step="1"
                               class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
                    </div>
                    <div>
                        <label for="experience_years" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Experience (Yrs)</label>
                        <input id="experience_years" type="number" name="experience_years" value="{{ old('experience_years', 2) }}" min="0" max="50"
                               class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
                    </div>
                </div>

                <div>
                    <label for="qualification" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Highest Degree / Qualification</label>
                    <input id="qualification" type="text" name="qualification" value="{{ old('qualification') }}" placeholder="e.g. M.Sc. in Applied Mathematics"
                           class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Select Subjects You Teach</label>
                    <div class="grid grid-cols-2 gap-2 max-h-36 overflow-y-auto p-2 rounded-2xl border border-slate-200 bg-slate-50/50">
                        @foreach($subjects as $subj)
                            <label class="flex items-center gap-2 p-1.5 hover:bg-white rounded-lg text-xs font-medium cursor-pointer">
                                <input type="checkbox" name="subjects[]" value="{{ $subj->id }}" class="rounded text-brand-800 focus:ring-brand-800 h-3.5 w-3.5">
                                <span class="truncate">{{ $subj->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </template>

        <!-- Dynamic Fields for Student -->
        <template x-if="role === 'student'">
            <div class="space-y-4 pt-2 border-t border-slate-100">
                <div>
                    <label for="grade_level" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Grade / Academic Level</label>
                    <input id="grade_level" type="text" name="grade_level" value="{{ old('grade_level') }}" placeholder="e.g. Grade 10 / A-Levels / College Freshman"
                           class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
                </div>
                <div>
                    <label for="learning_goals" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">What are your learning goals?</label>
                    <textarea id="learning_goals" name="learning_goals" rows="2" placeholder="e.g. Prepare for IELTS test / Need calculus exam prep"
                              class="w-full text-sm font-medium px-4 py-2.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">{{ old('learning_goals') }}</textarea>
                </div>
            </div>
        </template>

        <!-- Password -->
        <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100">
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       placeholder="••••••••"
                       class="w-full text-sm font-medium px-4 py-3 rounded-2xl border @error('password') border-rose-400 bg-rose-50/30 @else border-slate-200 bg-slate-50/50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Confirm</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       placeholder="••••••••"
                       class="w-full text-sm font-medium px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
            </div>
        </div>
        @error('password')
            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
        @enderror

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="w-full bg-gradient-to-r from-accent-600 to-accent-700 hover:from-accent-700 hover:to-accent-800 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-accent-600/20 hover:shadow-xl transition-all text-sm flex items-center justify-center gap-2">
                <span>Create My Account</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>
    </form>

    <!-- Switch to Login -->
    <div class="mt-6 text-center text-xs text-slate-500 pt-4 border-t border-slate-100">
        Already have an account? 
        <a href="{{ route('login') }}" class="font-bold text-brand-800 hover:text-brand-900 hover:underline">
            Sign In Here
        </a>
    </div>
</div>
@endsection
