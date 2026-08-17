@extends('layouts.dashboard')

@section('title', 'Admin Tutor Verifications')
@section('header', 'Tutor Verification Queue')
@section('subheader', 'Verify educational degrees and credentials to grant verified badges')

@section('content')
<div class="space-y-6">
    
    <!-- Tab Filters -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('admin.verifications.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'pending' ? 'bg-brand-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            Pending Review ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.verifications.index', ['status' => 'verified']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'verified' ? 'bg-brand-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            Verified Tutors ({{ $counts['verified'] }})
        </a>
    </div>

    @if($tutors->isEmpty())
        <div class="bg-white p-12 rounded-3xl border border-slate-200/80 text-center space-y-3 shadow-sm">
            <i class="fa-regular fa-circle-check text-4xl text-emerald-400"></i>
            <h3 class="text-base font-bold text-slate-900">No tutors currently in this queue</h3>
            <p class="text-xs text-slate-500">All registered tutors have been processed.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($tutors as $tProfile)
                <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm space-y-4 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3.5">
                                <img src="{{ $tProfile->user->avatar_url }}" alt="{{ $tProfile->user->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100">
                                <div>
                                    <h4 class="text-base font-bold text-slate-900">{{ $tProfile->user->name }}</h4>
                                    <p class="text-xs text-slate-500">{{ $tProfile->user->email }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5"><i class="fa-solid fa-location-dot"></i> {{ $tProfile->user->city ?: 'Not specified' }}</p>
                                </div>
                            </div>
                            @if($tProfile->is_verified)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Verified</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Pending</span>
                            @endif
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs text-slate-700">
                            <p><strong>Qualification:</strong> {{ $tProfile->qualification }}</p>
                            @if($tProfile->institution)
                                <p><strong>Institution:</strong> {{ $tProfile->institution }}</p>
                            @endif
                            <p><strong>Experience:</strong> {{ $tProfile->experience_years }} Years Teaching</p>
                            <p><strong>Hourly Rate:</strong> ${{ number_format($tProfile->hourly_rate, 2) }}/hr</p>
                            <p><strong>Headline:</strong> {{ $tProfile->headline }}</p>
                        </div>

                        <!-- Subjects -->
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($tProfile->subjects as $s)
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $s->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        @if(!$tProfile->is_verified)
                            <form method="POST" action="{{ route('admin.verifications.verify', $tProfile->id) }}">
                                @csrf
                                <button type="submit" class="bg-accent-600 hover:bg-accent-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                                    <i class="fa-solid fa-check"></i>
                                    <span>Approve & Verify</span>
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.verifications.reject', $tProfile->id) }}">
                                @csrf
                                <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-rose-600 font-bold text-xs px-4 py-2.5 rounded-xl transition-all">
                                    Revoke Verified Badge
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $tutors->links() }}
        </div>
    @endif

</div>
@endsection
