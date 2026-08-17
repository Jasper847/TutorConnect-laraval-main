@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('header', 'Platform Overview & Control')
@section('subheader', 'System-wide metrics, tutor verification queue, and user growth')

@section('content')
<div class="space-y-8">
    
    <!-- Admin KPI Cards (Reusable x-stat-card Components) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            icon="fa-solid fa-users" 
            :value="$stats['total_users']" 
            label="Total Users" 
            color="primary" 
            :subtext="$stats['total_students'] . ' Students, ' . $stats['total_tutors'] . ' Tutors'"
        />
        <x-stat-card 
            icon="fa-solid fa-shield-halved" 
            :value="$stats['pending_verifications']" 
            label="Pending Verifications" 
            color="amber" 
            subtext="Tutors awaiting review"
        />
        <x-stat-card 
            icon="fa-solid fa-calendar-check" 
            :value="$stats['total_bookings']" 
            label="Total Bookings" 
            color="blue" 
            :subtext="$stats['completed_bookings'] . ' Completed'"
        />
        <x-stat-card 
            icon="fa-solid fa-dollar-sign" 
            :value="'PKR ' . number_format($stats['total_revenue'], 0)" 
            label="Sandbox Revenue" 
            color="emerald" 
            subtext="Stripe Demo Transactions"
        />
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Pending Verifications Queue -->
        <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">Tutors Awaiting Verification</h3>
                    <p class="text-xs text-slate-500">Review credentials and grant verified status</p>
                </div>
                <a href="{{ route('admin.verifications.index') }}" class="text-xs font-bold text-primary-800 hover:underline">View All</a>
            </div>

            @if($pendingTutors->isEmpty())
                <div class="p-6 rounded-xl bg-slate-50 text-center text-xs text-slate-400">
                    <p>All tutor profiles are currently reviewed and up to date.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($pendingTutors as $pTutor)
                        <div class="p-4 rounded-xl border border-gray-100 bg-slate-50/50 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $pTutor->user->avatar_url }}" alt="{{ $pTutor->user->name }}" class="w-10 h-10 rounded-xl object-cover">
                                <div>
                                    <h4 class="text-xs font-bold font-heading text-slate-900">{{ $pTutor->user->name }}</h4>
                                    <p class="text-[11px] text-slate-500">{{ $pTutor->education ?: $pTutor->qualification }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.verifications.verify', $pTutor->id) }}">
                                    @csrf
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-semibold px-3 py-1.5 rounded-xl transition-all shadow-sm">
                                        Verify
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Registrations -->
        <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">Recent User Signups</h3>
                    <p class="text-xs text-slate-500">Newly registered students and mentors</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-primary-800 hover:underline">Manage Users</a>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($recentUsers as $u)
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" class="w-8 h-8 rounded-full object-cover">
                            <div>
                                <p class="font-bold text-slate-900">{{ $u->name }}</p>
                                <p class="text-[11px] text-slate-400">{{ $u->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-semibold {{ $u->role === 'tutor' ? 'bg-purple-100 text-purple-800' : ($u->role === 'admin' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($u->role) }}
                            </span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">{{ $u->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection