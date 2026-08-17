<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TutorConnect') }} - @yield('title', 'Dashboard')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        accent: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
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
    </style>
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50 min-h-full flex" x-data="{ sidebarOpen: false }">

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

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200/80 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
           :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
        <div>
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-brand-800 to-accent-600 flex items-center justify-center text-white shadow-md shadow-brand-800/20 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-extrabold tracking-tight text-slate-900 leading-none">
                            Tutor<span class="text-accent-600">Connect</span>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">
                            @if(auth()->user()->isAdmin()) Admin Console @elseif(auth()->user()->isTutor()) Tutor Portal @else Student Portal @endif
                        </span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Profile Summary Widget -->
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3.5">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-11 h-11 rounded-2xl object-cover ring-2 ring-brand-800/10">
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</h4>
                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <!-- Navigation Links by Role -->
            <nav class="p-4 space-y-1 text-sm font-semibold">
                @if(auth()->user()->isStudent())
                    <p class="px-3 text-[11px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Student Menu</p>
                    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.dashboard') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
                    </a>
                    <a href="{{ route('tutors.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutors.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Find Tutors
                    </a>
                    <a href="{{ route('student.bookings.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.bookings.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3"><i class="fa-solid fa-calendar-check w-5 text-center"></i> My Bookings</div>
                    </a>
                    <a href="{{ route('student.messages.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.messages.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3"><i class="fa-solid fa-comments w-5 text-center"></i> Messages</div>
                    </a>
                    <a href="{{ route('student.materials.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.materials.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-file-lines w-5 text-center"></i> Study Materials
                    </a>
                    <a href="{{ route('student.profile.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.profile.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-regular fa-user w-5 text-center"></i> My Profile
                    </a>

                @elseif(auth()->user()->isTutor())
                    <p class="px-3 text-[11px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Tutor Menu</p>
                    <a href="{{ route('tutor.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.dashboard') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
                    </a>
                    <a href="{{ route('tutor.bookings.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.bookings.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3"><i class="fa-solid fa-calendar-days w-5 text-center"></i> Booking Requests</div>
                    </a>
                    <a href="{{ route('tutor.availability.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.availability.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-regular fa-clock w-5 text-center"></i> Availability Schedule
                    </a>
                    <a href="{{ route('tutor.messages.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.messages.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3"><i class="fa-solid fa-comments w-5 text-center"></i> Student Messages</div>
                    </a>
                    <a href="{{ route('tutor.materials.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.materials.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-folder-open w-5 text-center"></i> Upload Materials
                    </a>
                    <a href="{{ route('tutor.reviews.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.reviews.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-star w-5 text-center"></i> Student Reviews
                    </a>
                    <a href="{{ route('tutor.profile.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('tutor.profile.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-regular fa-id-badge w-5 text-center"></i> Tutor Profile
                    </a>

                @elseif(auth()->user()->isAdmin())
                    <p class="px-3 text-[11px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">Admin Control Center</p>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-chart-line w-5 text-center"></i> Overview & Stats
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-users w-5 text-center"></i> User Management
                    </a>
                    <a href="{{ route('admin.verifications.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.verifications.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-shield-halved w-5 text-center"></i> Tutor Verifications
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.bookings.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-calendar-check w-5 text-center"></i> All Bookings
                    </a>
                    <a href="{{ route('admin.subjects.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.subjects.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-tags w-5 text-center"></i> Subject Catalog
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.reviews.*') ? 'bg-brand-800 text-white shadow-md shadow-brand-800/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-star-half-stroke w-5 text-center"></i> Moderate Reviews
                    </a>
                @endif
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-100">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <i class="fa-solid fa-globe w-5 text-center text-slate-400"></i> Public Website
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-colors text-left">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center text-rose-500"></i> Log Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-slate-200/80 px-6 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 leading-none">@yield('header', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500 mt-1">@yield('subheader', 'Manage your sessions and learning activities')</p>
                </div>
            </div>

            <!-- Quick Action / Live Badge -->
            <div class="flex items-center gap-4">
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Stripe Sandbox Active
                </span>
                <a href="{{ route('home') }}" target="_blank" class="p-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm" title="View Marketplace">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 sm:px-8 pt-6">
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-info text-blue-600 text-lg"></i>
                    <span class="text-sm font-medium">{{ session('info') }}</span>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1 p-6 sm:p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
