@extends('layouts.app')

@section('title', 'Manage Tutors & Verifications')
@section('header', 'Tutor Directory & Verifications')
@section('subheader', 'Inspect educator qualifications, verify profiles, and manage teaching status')

@section('content')
<div class="space-y-6">
    
    <!-- Filter & Search Bar -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        
        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.tutors.index') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all {{ !request('status') ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                All Tutors
            </a>
            <a href="{{ route('admin.tutors.index', ['status' => 'verified']) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all {{ request('status') === 'verified' ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Verified Only
            </a>
            <a href="{{ route('admin.tutors.index', ['status' => 'unverified']) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all {{ request('status') === 'unverified' ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Pending Verification
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.tutors.index') }}" class="flex items-center gap-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name or subject..."
                       class="text-xs font-medium pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 w-64">
            </div>
            <button type="submit" class="bg-primary-800 hover:bg-primary-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm">
                Search
            </button>
        </form>

    </div>

    <!-- Tutors Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        @if($tutors->isEmpty())
            <p class="text-xs text-slate-400 py-12 text-center">No tutor profiles found matching your search.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-gray-100 text-slate-400 uppercase font-semibold">
                        <tr>
                            <th class="p-4">Tutor</th>
                            <th class="p-4">Subjects</th>
                            <th class="p-4">Hourly Rate</th>
                            <th class="p-4">Rating</th>
                            <th class="p-4">Verification</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($tutors as $tutor)
                            @php
                                $subjs = is_array($tutor->subjects) ? $tutor->subjects : ($tutor->subjects ? json_decode($tutor->subjects, true) : []);
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $tutor->user->avatar_url }}" alt="{{ $tutor->user->name }}" class="w-11 h-11 rounded-xl object-cover ring-2 ring-gray-100">
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $tutor->user->name }}</p>
                                            <p class="text-[11px] text-slate-400">{{ $tutor->education ?: $tutor->qualification }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach(array_slice($subjs, 0, 3) as $s)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700">
                                                {{ is_array($s) ? ($s['name'] ?? '') : $s }}
                                            </span>
                                        @endforeach
                                        @if(count($subjs) > 3)
                                            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-semibold bg-slate-50 text-slate-400">+{{ count($subjs) - 3 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 font-bold text-slate-900">
                                    PKR {{ number_format($tutor->hourly_rate, 0) }}<span class="text-[10px] font-normal text-slate-400">/hr</span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center text-amber-400">
                                        <i class="fa-solid fa-star text-xs"></i>
                                        <span class="ml-1 font-bold text-slate-900">{{ number_format($tutor->avg_rating ?? ($tutor->rating_cache ?? 5.0), 1) }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    @if($tutor->is_verified)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            <i class="fa-solid fa-check text-[9px]"></i> Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            Pending Review
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right space-x-1.5">
                                    <a href="{{ route('student.tutors.show', $tutor->user_id) }}" target="_blank" 
                                       class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700">
                                        View Profile
                                    </a>

                                    @if(!$tutor->is_verified)
                                        <form method="POST" action="{{ route('admin.tutors.verify', $tutor->id) }}" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm">
                                                Verify
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.tutors.unverify', $tutor->id) }}" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-200 hover:bg-slate-300 text-slate-700">
                                                Unverify
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.toggle', $tutor->user_id) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $tutor->user->is_active ? 'bg-rose-50 text-rose-600 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700' }}">
                                            {{ $tutor->user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $tutors->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
