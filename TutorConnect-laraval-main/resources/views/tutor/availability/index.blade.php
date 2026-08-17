@extends('layouts.app')

@section('title', 'Weekly Availability Schedule')
@section('header', 'Weekly Availability Schedule')
@section('subheader', 'Configure your active tutoring time slots for Monday through Sunday')

@section('content')
<div class="max-w-4xl space-y-8">
    
    <!-- Info Banner -->
    <div class="bg-primary-50 border border-primary-100 rounded-xl p-5 flex items-start gap-4 text-xs text-primary-900">
        <i class="fa-solid fa-calendar-clock text-primary-800 text-lg mt-0.5"></i>
        <div class="space-y-1">
            <h4 class="font-bold font-heading text-primary-900">How Availability Works</h4>
            <p class="leading-relaxed">
                Students can only request sessions during the days and hours you designate as available below. You can toggle off any day of the week when you are off or adjust hours dynamically.
            </p>
        </div>
    </div>

    <!-- Weekly Builder Form -->
    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
        
        <div class="border-b border-gray-100 pb-4 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold font-heading text-slate-900">7-Day Weekly Schedule Matrix</h3>
                <p class="text-xs text-slate-500">Toggle available days and choose your teaching hours</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <i class="fa-solid fa-clock mr-1"></i> 24-Hour Format
            </span>
        </div>

        <form method="POST" action="{{ route('tutor.availability.update') }}" class="space-y-5">
            @csrf

            <div class="divide-y divide-gray-100">
                @foreach($days as $day)
                    @php
                        $dayKey = strtolower($day);
                        $slot = $existingSlots->get($dayKey);
                        $isAvailable = (bool) $slot;
                        $startTime = $slot ? date('H:i', strtotime($slot->start_time)) : '10:00';
                        $endTime = $slot ? date('H:i', strtotime($slot->end_time)) : '18:00';
                    @endphp

                    <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4" 
                         x-data="{ enabled: {{ $isAvailable ? 'true' : 'false' }} }">
                        
                        <!-- Day Label & Toggle -->
                        <div class="flex items-center gap-4 w-44">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="days[{{ $day }}][available]" value="1" x-model="enabled" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                            
                            <div>
                                <span class="text-sm font-bold font-heading text-slate-900">{{ $day }}</span>
                                <span class="block text-[10px] font-semibold" :class="enabled ? 'text-emerald-600' : 'text-slate-400'" 
                                      x-text="enabled ? 'Available' : 'Unavailable'"></span>
                            </div>
                        </div>

                        <!-- Start & End Time Inputs -->
                        <div class="flex items-center gap-3" :class="{ 'opacity-30 pointer-events-none': !enabled }">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-400">Start Time:</span>
                                <input type="time" name="days[{{ $day }}][start_time]" value="{{ $startTime }}" 
                                       class="text-xs font-semibold px-3 py-2 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20">
                            </div>

                            <span class="text-slate-400 text-xs font-bold">to</span>

                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-400">End Time:</span>
                                <input type="time" name="days[{{ $day }}][end_time]" value="{{ $endTime }}" 
                                       class="text-xs font-semibold px-3 py-2 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20">
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Action Button -->
            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-primary-800 hover:bg-primary-900 text-white font-semibold text-xs px-8 py-3 rounded-xl shadow-md shadow-primary-800/20 hover:shadow-lg transition-all flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Save Weekly Availability</span>
                </button>
            </div>
        </form>

    </div>

    <!-- Visual Weekly Calendar Slots Preview -->
    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-4">
        <h3 class="text-sm font-bold font-heading uppercase tracking-wider text-slate-400">Weekly Schedule Summary</h3>
        
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
            @foreach($days as $d)
                @php
                    $dKey = strtolower($d);
                    $slotObj = $existingSlots->get($dKey);
                @endphp
                <div class="p-3.5 rounded-xl border {{ $slotObj ? 'border-emerald-200 bg-emerald-50/50' : 'border-gray-100 bg-slate-50/50 opacity-60' }} text-center space-y-1">
                    <span class="text-xs font-bold font-heading uppercase block {{ $slotObj ? 'text-emerald-800' : 'text-slate-400' }}">
                        {{ substr($d, 0, 3) }}
                    </span>
                    @if($slotObj)
                        <p class="text-[11px] font-semibold text-slate-800">
                            {{ date('g:i A', strtotime($slotObj->start_time)) }}
                        </p>
                        <p class="text-[10px] text-slate-500">to</p>
                        <p class="text-[11px] font-semibold text-slate-800">
                            {{ date('g:i A', strtotime($slotObj->end_time)) }}
                        </p>
                    @else
                        <p class="text-[11px] font-medium text-slate-400 py-2">Off</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
