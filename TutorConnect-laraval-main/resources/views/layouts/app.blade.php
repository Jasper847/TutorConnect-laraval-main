<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TutorConnect') }} - @yield('title', 'Dashboard')</title>

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
<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-full flex flex-col" x-data="{ sidebarOpen: false }">

    <div class="flex-1 flex">
        <!-- Off-canvas Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" x-cloak 
             @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden">
        </div>

        <!-- Sidebar Navigation (Role-Aware) -->
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-sm"
               :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <div>
                <!-- Brand Header -->
                <div class="h-20 flex items-center justify-between px-6 border-b border-gray-100">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary-800 to-emerald-600 flex items-center justify-center text-white shadow-md shadow-primary-800/20 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-graduation-cap text-lg"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold font-heading tracking-tight text-slate-900 leading-none">
                                Tutor<span class="text-emerald-600">Connect</span>
                            </span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">
                                @if(auth()->check() && auth()->user()->isAdmin()) Admin Portal @elseif(auth()->check() && auth()->user()->isTutor()) Tutor Portal @elseif(auth()->check()) Student Portal @else Portal @endif
                            </span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                @auth
                    <!-- User Profile Quick View -->
                    <div class="p-5 border-b border-gray-100 bg-slate-50/50 flex items-center gap-3.5">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-11 h-11 rounded-xl object-cover ring-2 ring-primary-800/10">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold font-heading text-slate-900 truncate">{{ auth()->user()->name }}</h4>
                            <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <!-- Role-Aware Navigation Links -->
                    <nav class="p-4 space-y-1.5 text-sm font-medium">
                        @if(auth()->user()->isStudent())
                            <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Student Menu</p>
                            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.dashboard') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
                            </a>
                            <a href="{{ route('tutors.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutors.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Find Tutors
                            </a>
                            <a href="{{ route('student.bookings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.bookings.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-calendar-check w-5 text-center"></i> My Bookings
                            </a>
                            <a href="{{ route('student.messages.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.messages.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-comments w-5 text-center"></i> Messages
                            </a>
                            <a href="{{ route('student.materials.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.materials.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-file-lines w-5 text-center"></i> Study Materials
                            </a>
                            <a href="{{ route('student.profile.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.profile.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-regular fa-user w-5 text-center"></i> My Profile
                            </a>

                        @elseif(auth()->user()->isTutor())
                            <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Tutor Menu</p>
                            <a href="{{ route('tutor.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.dashboard') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
                            </a>
                            <a href="{{ route('tutor.bookings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.bookings.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-calendar-days w-5 text-center"></i> Booking Requests
                            </a>
                            <a href="{{ route('tutor.availability.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.availability.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-regular fa-clock w-5 text-center"></i> Availability Slots
                            </a>
                            <a href="{{ route('tutor.messages.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.messages.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-comments w-5 text-center"></i> Student Messages
                            </a>
                            <a href="{{ route('tutor.materials.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.materials.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-folder-open w-5 text-center"></i> Study Materials
                            </a>
                            <a href="{{ route('tutor.reviews.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.reviews.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-star w-5 text-center"></i> Reviews & Ratings
                            </a>
                            <a href="{{ route('tutor.profile.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.profile.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-regular fa-id-badge w-5 text-center"></i> Tutor Profile
                            </a>

                        @elseif(auth()->user()->isAdmin())
                            <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Admin Console</p>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-users w-5 text-center"></i> Users
                            </a>
                            <a href="{{ route('admin.tutors.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.tutors.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-chalkboard-user w-5 text-center"></i> Tutors
                            </a>
                            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.bookings.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-calendar-check w-5 text-center"></i> Bookings
                            </a>
                            <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.reviews.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-star-half-stroke w-5 text-center"></i> Reviews
                            </a>
                            <a href="{{ route('admin.stats.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.stats.*') ? 'bg-primary-800 text-white font-semibold shadow-md shadow-primary-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <i class="fa-solid fa-chart-line w-5 text-center"></i> Statistics
                            </a>
                        @endif
                    </nav>
                @endauth
            </div>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-100">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                    <i class="fa-solid fa-globe w-5 text-center text-slate-400"></i> Public Website
                </a>
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-colors text-left">
                            <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center text-rose-500"></i> Log Out
                        </button>
                    </form>
                @endauth
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar -->
            <header class="h-20 bg-white border-b border-gray-100 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold font-heading text-slate-900 leading-none">@yield('header', 'Dashboard')</h1>
                        <p class="text-xs text-slate-500 mt-1">@yield('subheader', 'Manage your sessions and learning activities')</p>
                    </div>
                </div>

                <!-- Right Navbar Actions: Bell, Avatar, Logout -->
                <div class="flex items-center gap-4">
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Demo Sandbox Active
                    </span>

                    <!-- Notification Bell Icon -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2.5 rounded-xl border border-gray-100 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors relative">
                            <i class="fa-regular fa-bell text-base"></i>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-emerald-600 ring-2 ring-white"></span>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 p-4 z-50 space-y-3">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                <h4 class="text-xs font-bold font-heading text-slate-900">Notifications</h4>
                                <span class="text-[10px] font-semibold text-emerald-600">All caught up</span>
                            </div>
                            <p class="text-xs text-slate-500 text-center py-2">No new pending alerts.</p>
                        </div>
                    </div>

                    @auth
                        <!-- User Dropdown & Avatar -->
                        <div class="flex items-center gap-3 pl-2 border-l border-gray-100">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-xl object-cover ring-2 ring-primary-800/10">
                            <div class="hidden md:block text-left">
                                <p class="text-xs font-bold font-heading text-slate-900 truncate max-w-[120px]">{{ auth()->user()->name }}</p>
                                <span class="text-[10px] font-semibold text-slate-400 capitalize">{{ auth()->user()->role }}</span>
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Alerts Banner Component / Flash Messages -->
            <div class="px-6 sm:px-8 pt-6">
                @if(session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif
                @if(session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif
                @if(session('info'))
                    <x-alert type="info" :message="session('info')" />
                @endif
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-6 sm:p-8">
                @yield('content')
            </main>

            <!-- Authenticated Dashboard Footer with Copyright -->
            <footer class="bg-white border-t border-gray-100 py-4 px-6 sm:px-8 text-center text-xs text-slate-400">
                <p>&copy; 2025 TutorConnect. All rights reserved. Professional Tutor Marketplace.</p>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>