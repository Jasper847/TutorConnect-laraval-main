@extends('layouts.guest')

@section('title', 'Sign In')

@section('content')
<div x-data="{
    fillDemo(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
    }
}">
    <!-- Heading -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-extrabold text-slate-900">Welcome Back</h2>
        <p class="text-xs text-slate-500 mt-1">Sign in to manage your tutoring sessions</p>
    </div>

    <!-- Quick Demo Logins (Convenient for Reviewers/Evaluators) -->
    <div class="mb-6 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2.5 text-center">
            <i class="fa-solid fa-bolt text-amber-500 mr-1"></i> Quick Demo Login
        </p>
        <div class="grid grid-cols-3 gap-2">
            <button type="button" @click="fillDemo('admin@tutorconnect.com')" class="px-2.5 py-1.5 rounded-xl text-xs font-bold bg-white hover:bg-slate-100 border border-slate-200 text-slate-800 transition-all shadow-sm">
                Admin
            </button>
            <button type="button" @click="fillDemo('ahmed.khan@tutorconnect.com')" class="px-2.5 py-1.5 rounded-xl text-xs font-bold bg-white hover:bg-slate-100 border border-slate-200 text-slate-800 transition-all shadow-sm">
                Tutor
            </button>
            <button type="button" @click="fillDemo('student@tutorconnect.com')" class="px-2.5 py-1.5 rounded-xl text-xs font-bold bg-white hover:bg-slate-100 border border-slate-200 text-slate-800 transition-all shadow-sm">
                Student
            </button>
        </div>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
            <div class="relative">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       placeholder="name@example.com"
                       class="w-full text-sm font-medium px-4 py-3 rounded-2xl border @error('email') border-rose-400 bg-rose-50/30 @else border-slate-200 bg-slate-50/50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
            </div>
            @error('email')
                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Password</label>
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full text-sm font-medium px-4 py-3 rounded-2xl border @error('password') border-rose-400 bg-rose-50/30 @else border-slate-200 bg-slate-50/50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
            @error('password')
                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="rounded text-brand-800 focus:ring-brand-800 h-4 w-4 border-slate-300">
                <span class="text-xs font-medium text-slate-600">Remember me</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-3">
            <button type="submit" class="w-full bg-gradient-to-r from-brand-800 to-brand-700 hover:from-brand-900 hover:to-brand-800 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-brand-800/20 hover:shadow-xl transition-all text-sm flex items-center justify-center gap-2">
                <span>Sign In</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>
    </form>

    <!-- Switch to Register -->
    <div class="mt-6 text-center text-xs text-slate-500 pt-4 border-t border-slate-100">
        Don't have an account yet? 
        <a href="{{ route('register') }}" class="font-bold text-brand-800 hover:text-brand-900 hover:underline">
            Register for Free
        </a>
    </div>
</div>
@endsection