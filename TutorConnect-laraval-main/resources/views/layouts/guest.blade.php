<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TutorConnect') }} - @yield('title', 'Find Top Rated Tutors')</title>

    <!-- Google Fonts: Inter (Body) & Poppins (Headings) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        },
                        brand: {
                            blue: '#1e40af',
                            emerald: '#059669',
                            slate: '#1e293b',
                            bg: '#f8fafc',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-full flex flex-col justify-between" x-data="{ mobileMenuOpen: false }">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary-800 to-emerald-600 flex items-center justify-center text-white shadow-md shadow-primary-800/20 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold font-heading tracking-tight text-slate-900 leading-none">
                        Tutor<span class="text-emerald-600">Connect</span>
                    </span>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                    <a href="{{ route('home') }}" class="hover:text-primary-800 transition-colors {{ request()->routeIs('home') ? 'text-primary-800 font-semibold' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('tutors.index') }}" class="hover:text-primary-800 transition-colors {{ request()->routeIs('tutors.*') ? 'text-primary-800 font-semibold' : '' }}">
                        Find Tutors
                    </a>
                    <a href="{{ route('home') }}#how-it-works" class="hover:text-primary-800 transition-colors">
                        How It Works
                    </a>
                    <a href="{{ route('about') }}" class="hover:text-primary-800 transition-colors {{ request()->routeIs('about') ? 'text-primary-800 font-semibold' : '' }}">
                        About Us
                    </a>
                </nav>

                <!-- Desktop Auth CTA Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isTutor() ? route('tutor.dashboard') : route('student.dashboard')) }}" 
                           class="bg-primary-800 hover:bg-primary-900 text-white text-xs font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-primary-800/20 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-gauge"></i>
                            <span>Go to Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-700 hover:text-primary-800 px-4 py-2.5 rounded-xl hover:bg-slate-100 transition-all">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-emerald-600/20 hover:shadow-lg transition-all flex items-center gap-1.5">
                            <span>Register Free</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2.5 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none">
                        <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark text-xl' : 'fa-bars text-xl'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenuOpen" x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-white border-b border-gray-100 px-4 pt-2 pb-6 space-y-3 shadow-lg">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">Home</a>
            <a href="{{ route('tutors.index') }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">Find Tutors</a>
            <a href="{{ route('home') }}#how-it-works" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">How It Works</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">About Us</a>
            
            <div class="pt-4 border-t border-gray-100 flex flex-col gap-2">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isTutor() ? route('tutor.dashboard') : route('student.dashboard')) }}" 
                       class="w-full text-center bg-primary-800 text-white text-xs font-semibold py-3 rounded-xl shadow">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center bg-slate-100 text-slate-800 text-xs font-semibold py-2.5 rounded-xl">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="w-full text-center bg-emerald-600 text-white text-xs font-semibold py-2.5 rounded-xl shadow">
                        Register Free
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Full-width Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Public Footer -->
    <footer class="bg-slate-900 text-white border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Column 1: Brand & Bio -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-primary-800 to-emerald-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <span class="text-xl font-bold font-heading tracking-tight">Tutor<span class="text-emerald-500">Connect</span></span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed font-sans">
                        Premier 1-on-1 personalized tutor marketplace. Find verified educators for Calculus, Physics, Coding, and Language exams.
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="space-y-3 text-xs">
                    <h4 class="font-bold font-heading uppercase tracking-wider text-slate-200">Explore</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('tutors.index') }}" class="hover:text-white transition-colors">Find Tutors</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('register') }}?role=tutor" class="hover:text-white transition-colors">Become a Tutor</a></li>
                    </ul>
                </div>

                <!-- Column 3: Subjects -->
                <div class="space-y-3 text-xs">
                    <h4 class="font-bold font-heading uppercase tracking-wider text-slate-200">Popular Subjects</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="{{ route('tutors.index') }}?q=Mathematics" class="hover:text-white transition-colors">Mathematics & Calculus</a></li>
                        <li><a href="{{ route('tutors.index') }}?q=Physics" class="hover:text-white transition-colors">Physics & Mechanics</a></li>
                        <li><a href="{{ route('tutors.index') }}?q=Computer+Science" class="hover:text-white transition-colors">Computer Science & Python</a></li>
                        <li><a href="{{ route('tutors.index') }}?q=English" class="hover:text-white transition-colors">English & IELTS</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact & Sandbox Note -->
                <div class="space-y-3 text-xs text-slate-400">
                    <h4 class="font-bold font-heading uppercase tracking-wider text-slate-200">Test & Demo</h4>
                    <p class="leading-relaxed">
                        Operating in demonstration mode. Payments are simulated with Stripe Sandbox test cards.
                    </p>
                    <div class="pt-2 text-emerald-400 font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-shield-check"></i> 100% Vetted Educators
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="mt-12 pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; 2025 TutorConnect. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('about') }}" class="hover:text-slate-400">Privacy Policy</a>
                    <a href="{{ route('about') }}" class="hover:text-slate-400">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
