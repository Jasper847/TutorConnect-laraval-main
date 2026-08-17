@extends('layouts.app')

@section('title', 'Book Session with ' . $tutor->name)
@section('header', 'Book a Tutoring Session')
@section('subheader', 'Schedule a 1-on-1 personalized learning session with ' . $tutor->name)

@section('content')
<div class="max-w-4xl" x-data="{
    selectedSubject: '{{ old('subject', $tutorSubjects[0] ?? '') }}',
    selectedDate: '{{ old('booking_date', date('Y-m-d')) }}',
    selectedTime: '{{ old('start_time', '10:00') }}',
    hourlyRate: {{ (int) $profile->hourly_rate }},
    slotStatus: '',
    isLoading: false,

    fetchSlots() {
        if (!this.selectedDate) return;
        const d = new Date(this.selectedDate);
        const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        const day = dayNames[d.getUTCDay()];
        
        this.isLoading = true;
        fetch('{{ route('student.tutors.slots', $tutor->id) }}?day=' + day)
            .then(res => res.json())
            .then(data => {
                this.isLoading = false;
                if (data.available) {
                    this.slotStatus = 'Tutor available: ' + data.display;
                    this.selectedTime = data.start_time;
                } else {
                    this.slotStatus = '⚠️ Note: Tutor normally takes this day off, but you may still submit a request.';
                }
            })
            .catch(() => { this.isLoading = false; });
    }
}" x-init="fetchSlots()">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Booking Form (Left 2 Cols) -->
        <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
            
            <!-- Tutor Quick Info Header -->
            <div class="flex items-center gap-4 pb-6 border-b border-gray-100">
                <img src="{{ $tutor->avatar_url }}" alt="{{ $tutor->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-gray-100">
                <div>
                    <h3 class="text-base font-bold font-heading text-slate-900">{{ $tutor->name }}</h3>
                    <p class="text-xs text-primary-800 font-semibold">{{ $profile->headline }}</p>
                    <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-0.5">
                        <span><i class="fa-solid fa-star text-amber-400"></i> {{ number_format($profile->avg_rating ?? ($profile->rating_cache ?? 5.0), 1) }}</span>
                        <span>PKR {{ number_format($profile->hourly_rate, 0) }}/hr</span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('student.bookings.store', $tutor->id) }}" class="space-y-6">
                @csrf

                <!-- Subject Selection -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Select Subject</label>
                    <select name="subject" x-model="selectedSubject" required
                            class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                        @foreach($tutorSubjects as $subj)
                            @php $subjVal = is_array($subj) ? ($subj['name'] ?? '') : $subj; @endphp
                            <option value="{{ $subjVal }}">{{ $subjVal }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Picker (with Tutor Available Days notice) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Select Date</label>
                        <span class="text-[11px] text-emerald-700 font-semibold">
                            Tutor Days: {{ $availableDays->implode(', ') ?: 'All Week' }}
                        </span>
                    </div>
                    <input type="date" name="booking_date" x-model="selectedDate" @change="fetchSlots()" min="{{ date('Y-m-d') }}" required
                           class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                    
                    <p class="text-xs mt-1.5 font-medium" :class="slotStatus.includes('⚠️') ? 'text-amber-600' : 'text-emerald-700'" x-text="slotStatus"></p>
                </div>

                <!-- Time Slot -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Start Time</label>
                    <input type="time" name="start_time" x-model="selectedTime" required
                           class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                    <p class="text-[11px] text-slate-400 mt-1">Standard 1-hour session duration.</p>
                </div>

                <!-- Notes / Topics to Cover (Optional Textarea) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Learning Goals / Notes for Tutor (Optional)</label>
                    <textarea name="notes" rows="3" placeholder="Specify any particular problem sets, chapters, or syllabus queries you want to focus on..."
                              class="w-full text-xs sm:text-sm font-medium p-4 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800 leading-relaxed">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('student.tutors.show', $tutor->id) }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs px-8 py-3 rounded-xl shadow-md shadow-emerald-600/20 hover:shadow-lg transition-all flex items-center gap-2">
                        <span>Proceed to Payment</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Order Summary Card (Right Col) -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-gray-100 pb-3">Booking Summary</h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tutor:</span>
                        <span class="font-bold text-slate-900">{{ $tutor->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Subject:</span>
                        <span class="font-bold text-primary-800" x-text="selectedSubject"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Date:</span>
                        <span class="font-bold text-slate-800" x-text="selectedDate"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Time:</span>
                        <span class="font-bold text-slate-800" x-text="selectedTime"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Duration:</span>
                        <span class="font-bold text-slate-800">1.0 Hour (Online)</span>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-between items-center text-sm">
                        <span class="font-bold font-heading text-slate-900">Total Price:</span>
                        <span class="text-base font-extrabold font-heading text-emerald-600">
                            PKR {{ number_format($profile->hourly_rate, 0) }}
                        </span>
                    </div>
                </div>

                <!-- Sandbox Badge -->
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-[11px] text-emerald-800 space-y-1">
                    <p class="font-bold"><i class="fa-solid fa-shield-check mr-1"></i> Sandbox Test Mode Active</p>
                    <p class="text-emerald-700">No real charges occur. You can complete checkout with a mock Stripe test card.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
