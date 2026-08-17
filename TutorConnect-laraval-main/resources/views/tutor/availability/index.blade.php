@extends('layouts.dashboard')

@section('title', 'Weekly Availability Schedule')
@section('header', 'Weekly Availability Schedule')
@section('subheader', 'Configure the time windows when students can book sessions with you')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
        
        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 flex items-start gap-3">
            <i class="fa-solid fa-circle-info text-blue-600 text-base mt-0.5"></i>
            <p class="text-xs text-blue-800 leading-relaxed font-medium">
                Students will only be allowed to book time slots within your active weekly schedule windows. Toggle the days you are open for teaching.
            </p>
        </div>

        <form method="POST" action="{{ route('tutor.availability.update') }}" class="space-y-4">
            @csrf

            <div class="divide-y divide-slate-100">
                @foreach($days as $day)
                    @php
                        $av = $availabilities->get($day);
                        $enabled = $av ? $av->is_available : in_array($day, ['monday','tuesday','wednesday','thursday','friday']);
                        $start = $av ? date('H:i', strtotime($av->start_time)) : '10:00';
                        $end = $av ? date('H:i', strtotime($av->end_time)) : '18:00';
                    @endphp
                    <div class="py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4" x-data="{ active: {{ $enabled ? 'true' : 'false' }} }">
                        <div class="flex items-center gap-3 w-36">
                            <input type="checkbox" name="days[{{ $day }}][enabled]" value="1" x-model="active" id="day_{{ $day }}"
                                   class="rounded text-brand-800 focus:ring-brand-800 h-4 w-4">
                            <label for="day_{{ $day }}" class="text-sm font-bold capitalize text-slate-800 cursor-pointer">
                                {{ $day }}
                            </label>
                        </div>

                        <div class="flex items-center gap-3" :class="{'opacity-40 pointer-events-none': !active}">
                            <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                <span>From:</span>
                                <input type="time" name="days[{{ $day }}][start_time]" value="{{ $start }}"
                                       class="text-xs font-medium px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none">
                            </div>
                            <span class="text-slate-400 text-xs">to</span>
                            <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                <span>To:</span>
                                <input type="time" name="days[{{ $day }}][end_time]" value="{{ $end }}"
                                       class="text-xs font-medium px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-brand-800 hover:bg-brand-900 text-white font-bold text-sm px-8 py-3 rounded-xl shadow-md transition-all">
                    Save Availability Schedule
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
