@extends('layouts.app')

@section('title', 'Admin Overview')
@section('header', 'Platform Overview & KPIs')
@section('subheader', 'System-wide metrics, booking volume trends, and real-time activity')

@section('content')
<div class="space-y-8">
    
    <!-- Pending Verifications Alert Banner -->
    @if($stats['pending_verifications'] > 0)
        <div class="p-5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold font-heading text-amber-900">
                        {{ $stats['pending_verifications'] }} Tutor {{ Str::plural('Profile', $stats['pending_verifications']) }} Awaiting Verification
                    </h4>
                    <p class="text-xs text-amber-700">Review educator degrees and credentials to grant verified badges.</p>
                </div>
            </div>
            <a href="{{ route('admin.tutors.index') }}?status=unverified" 
               class="inline-flex items-center gap-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition-all shrink-0">
                <span>Review Tutors</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    @endif

    <!-- 5 Stats Row (Total Users, Total Tutors, Total Students, Total Bookings, Revenue) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
        <x-stat-card 
            icon="fa-solid fa-users" 
            :value="$stats['total_users']" 
            label="Total Users" 
            color="primary" 
        />
        <x-stat-card 
            icon="fa-solid fa-chalkboard-user" 
            :value="$stats['total_tutors']" 
            label="Total Tutors" 
            color="emerald" 
        />
        <x-stat-card 
            icon="fa-solid fa-graduation-cap" 
            :value="$stats['total_students']" 
            label="Total Students" 
            color="purple" 
        />
        <x-stat-card 
            icon="fa-solid fa-calendar-check" 
            :value="$stats['total_bookings']" 
            label="Total Bookings" 
            color="blue" 
        />
        <x-stat-card 
            icon="fa-solid fa-dollar-sign" 
            :value="'PKR ' . number_format($stats['total_revenue'], 0)" 
            label="Demo Revenue" 
            color="amber" 
            subtext="Simulated PKR"
        />
    </div>

    <!-- Chart: Bookings per Month (CSS / Alpine.js Bar Chart) -->
    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div>
                <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">Monthly Bookings Trend</h3>
                <p class="text-xs text-slate-500">Booking volume over the past 6 months</p>
            </div>
            <span class="text-xs font-semibold text-primary-800 bg-primary-50 px-3 py-1 rounded-full">
                Past 6 Months
            </span>
        </div>

        <!-- Pure CSS/Tailwind Responsive Bar Chart -->
        <div class="pt-4 pb-2">
            <div class="grid grid-cols-6 gap-3 sm:gap-6 items-end h-48 border-b border-gray-200/80 px-4">
                @foreach($months as $m)
                    @php
                        $heightPercent = $maxMonthCount > 0 ? max(12, ($m['count'] / $maxMonthCount) * 100) : 12;
                    @endphp
                    <div class="flex flex-col items-center gap-2 group h-full justify-end">
                        <span class="text-[11px] font-bold text-slate-800 opacity-0 group-hover:opacity-100 transition-opacity">
                            {{ $m['count'] }}
                        </span>
                        <div class="w-full max-w-[48px] bg-gradient-to-t from-primary-800 to-emerald-500 rounded-t-xl group-hover:brightness-110 transition-all shadow-sm" 
                             style="height: {{ $heightPercent }}%"></div>
                    </div>
                @endforeach
            </div>

            <!-- X-Axis Labels -->
            <div class="grid grid-cols-6 gap-3 sm:gap-6 text-center text-xs font-bold text-slate-500 pt-3 px-4">
                @foreach($months as $m)
                    <span>{{ $m['label'] }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table (Last 10) -->
    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div>
                <h3 class="text-base sm:text-lg font-bold font-heading text-slate-900">Recent Platform Bookings (Last 10)</h3>
                <p class="text-xs text-slate-500">Live appointment transactions</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-primary-800 hover:underline">
                View All ({{ $stats['total_bookings'] }}) &rarr;
            </a>
        </div>

        @if($recentBookings->isEmpty())
            <p class="text-xs text-slate-400 py-6 text-center">No bookings placed on the platform yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-gray-100 text-slate-400 uppercase font-semibold">
                        <tr>
                            <th class="p-3.5">Code</th>
                            <th class="p-3.5">Student</th>
                            <th class="p-3.5">Tutor</th>
                            <th class="p-3.5">Subject</th>
                            <th class="p-3.5">Date & Time</th>
                            <th class="p-3.5">Amount</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentBookings as $b)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="p-3.5 font-mono font-bold text-slate-900">#{{ $b->booking_code }}</td>
                                <td class="p-3.5 font-medium text-slate-800">{{ $b->student->name }}</td>
                                <td class="p-3.5 font-medium text-slate-800">{{ $b->tutor->name }}</td>
                                <td class="p-3.5 font-semibold text-primary-800">{{ $b->subject }}</td>
                                <td class="p-3.5 text-slate-500">
                                    {{ $b->booking_date->format('M d, Y') }} at {{ date('g:i A', strtotime($b->start_time)) }}
                                </td>
                                <td class="p-3.5 font-bold text-slate-900">PKR {{ number_format($b->total_amount, 0) }}</td>
                                <td class="p-3.5">
                                    <x-booking-badge :status="$b->status" />
                                </td>
                                <td class="p-3.5 text-right">
                                    <a href="{{ route('admin.bookings.show', $b->id) }}" class="text-xs font-semibold text-primary-800 hover:underline">
                                        Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection