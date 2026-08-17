@extends('layouts.app')

@section('title', 'Platform Analytics & Statistics')
@section('header', 'Platform Analytics & Growth')
@section('subheader', 'Deep-dive into subject popularity, top-performing tutors, and monthly revenue metrics')

@section('content')
<div class="space-y-8">
    
    <!-- Top 2 Grid: Popular Subjects & Top Tutors -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Popular Subjects Breakdown -->
        <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-base font-bold font-heading text-slate-900">Most Popular Subjects</h3>
                    <p class="text-xs text-slate-500">Booking frequency by academic discipline</p>
                </div>
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                    Top Disciplines
                </span>
            </div>

            <div class="space-y-4">
                @forelse($popularSubjects as $subjectItem)
                    @php
                        $percentage = ($subjectItem->total / $maxSubjectCount) * 100;
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-semibold">
                            <span class="text-slate-800">{{ $subjectItem->subject }}</span>
                            <span class="text-primary-800 font-bold">{{ $subjectItem->total }} bookings</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-primary-800 to-emerald-500 h-full rounded-full transition-all" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">No bookings recorded yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Tutors by Booking Count -->
        <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-base font-bold font-heading text-slate-900">Top-Performing Tutors</h3>
                    <p class="text-xs text-slate-500">Ranked by completed & confirmed sessions</p>
                </div>
                <span class="text-xs font-semibold text-primary-800 bg-primary-50 px-2.5 py-1 rounded-full">
                    Leaderboard
                </span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($topTutors as $index => $tutorUser)
                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-6 text-xs font-extrabold font-heading text-slate-400">#{{ $index + 1 }}</span>
                            <img src="{{ $tutorUser->avatar_url }}" alt="{{ $tutorUser->name }}" class="w-9 h-9 rounded-xl object-cover ring-2 ring-gray-100">
                            <div>
                                <h4 class="text-xs font-bold font-heading text-slate-900">{{ $tutorUser->name }}</h4>
                                <p class="text-[11px] text-slate-400">{{ $tutorUser->tutorProfile?->headline ?: 'Educator' }}</p>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="text-xs font-bold font-heading text-primary-800 block">
                                {{ $tutorUser->tutor_bookings_count }} Bookings
                            </span>
                            <span class="text-[10px] text-amber-500 font-semibold">
                                ★ {{ number_format($tutorUser->tutorProfile?->avg_rating ?? 5.0, 1) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">No tutors available.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Bottom 2 Grid: Monthly Revenue & User Growth -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Monthly Revenue Summary (Demo) -->
        <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-base font-bold font-heading text-slate-900">Monthly Revenue (Sandbox)</h3>
                    <p class="text-xs text-slate-500">Gross processed volume per month in PKR</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-gray-100 text-slate-400 uppercase font-semibold">
                        <tr>
                            <th class="p-3">Month</th>
                            <th class="p-3 text-right">Processed Revenue (PKR)</th>
                            <th class="p-3 text-right">USD Equiv.</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($monthlyRevenue as $revRow)
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-3 font-bold text-slate-800">{{ $revRow['month'] }}</td>
                                <td class="p-3 text-right font-mono font-bold text-emerald-700">
                                    PKR {{ number_format($revRow['revenue'], 0) }}
                                </td>
                                <td class="p-3 text-right text-slate-500 font-mono">
                                    ~${{ number_format($revRow['revenue'] / 280, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- User Growth Table by Month -->
        <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-base font-bold font-heading text-slate-900">User Growth Trends</h3>
                    <p class="text-xs text-slate-500">New student and tutor acquisitions per month</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-gray-100 text-slate-400 uppercase font-semibold">
                        <tr>
                            <th class="p-3">Month</th>
                            <th class="p-3 text-center">New Students</th>
                            <th class="p-3 text-center">New Tutors</th>
                            <th class="p-3 text-right font-bold">Total Signups</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($userGrowth as $growthRow)
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-3 font-bold text-slate-800">{{ $growthRow['month'] }}</td>
                                <td class="p-3 text-center text-blue-700 font-semibold">+{{ $growthRow['students'] }}</td>
                                <td class="p-3 text-center text-purple-700 font-semibold">+{{ $growthRow['tutors'] }}</td>
                                <td class="p-3 text-right font-bold text-slate-900">+{{ $growthRow['total'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
