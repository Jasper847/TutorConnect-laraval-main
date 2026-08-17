@extends('layouts.dashboard')

@section('title', 'Tutor Booking Requests')
@section('header', 'Booking Requests & Appointments')
@section('subheader', 'Confirm new student requests and manage your scheduled sessions')

@section('content')
<div class="space-y-6">
    
    <!-- Tab Filters -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('tutor.bookings.index', ['tab' => 'all']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'all' ? 'bg-brand-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            All ({{ $counts['all'] }})
        </a>
        <a href="{{ route('tutor.bookings.index', ['tab' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'pending' ? 'bg-brand-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            Pending Requests ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('tutor.bookings.index', ['tab' => 'confirmed']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'confirmed' ? 'bg-brand-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            Confirmed ({{ $counts['confirmed'] }})
        </a>
        <a href="{{ route('tutor.bookings.index', ['tab' => 'completed']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'completed' ? 'bg-brand-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            Completed ({{ $counts['completed'] }})
        </a>
        <a href="{{ route('tutor.bookings.index', ['tab' => 'cancelled']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'cancelled' ? 'bg-brand-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            Cancelled ({{ $counts['cancelled'] }})
        </a>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white p-12 rounded-3xl border border-slate-200/80 text-center space-y-3 shadow-sm">
            <i class="fa-regular fa-calendar-xmark text-4xl text-slate-300"></i>
            <h3 class="text-base font-bold text-slate-900">No bookings in this category</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">New session bookings from students will show up here automatically.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <img src="{{ $booking->student->avatar_url }}" alt="{{ $booking->student->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100 shrink-0">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-bold text-slate-400">#{{ $booking->booking_code }}</span>
                                @if($booking->status === 'confirmed')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">Confirmed</span>
                                @elseif($booking->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">Pending</span>
                                @elseif($booking->status === 'completed')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">Completed</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">Cancelled</span>
                                @endif
                                <span class="text-xs font-semibold text-slate-500 uppercase">{{ ucfirst($booking->mode) }}</span>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 mt-1">
                                {{ $booking->student->name }} — <span class="text-brand-800 font-semibold">{{ $booking->subject?->name ?? 'Tutoring' }}</span>
                            </h3>

                            <div class="mt-2 flex flex-wrap items-center gap-4 text-xs font-medium text-slate-500">
                                <span><i class="fa-regular fa-calendar text-slate-400"></i> {{ $booking->booking_date->format('M d, Y') }}</span>
                                <span><i class="fa-regular fa-clock text-slate-400"></i> {{ date('g:i A', strtotime($booking->start_time)) }} - {{ date('g:i A', strtotime($booking->end_time)) }}</span>
                                <span><i class="fa-solid fa-dollar-sign text-slate-400"></i> ${{ number_format($booking->total_amount, 2) }}</span>
                            </div>

                            @if($booking->student_notes)
                                <p class="text-xs text-slate-600 italic mt-2">"{{ $booking->student_notes }}"</p>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto justify-end border-t lg:border-t-0 pt-3 lg:pt-0">
                        <a href="{{ route('tutor.messages.show', $booking->student_id) }}" class="p-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs" title="Message Student">
                            <i class="fa-regular fa-comment"></i>
                        </a>

                        @if($booking->status === 'pending')
                            <form method="POST" action="{{ route('tutor.bookings.confirm', $booking->id) }}">
                                @csrf
                                <button type="submit" class="bg-accent-600 hover:bg-accent-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                                    Accept
                                </button>
                            </form>
                            <form method="POST" action="{{ route('tutor.bookings.cancel', $booking->id) }}">
                                @csrf
                                <input type="hidden" name="cancellation_reason" value="Declined by tutor">
                                <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-rose-600 text-xs font-bold px-3 py-2.5 rounded-xl transition-all">
                                    Decline
                                </button>
                            </form>
                        @elseif($booking->status === 'confirmed')
                            <form method="POST" action="{{ route('tutor.bookings.complete', $booking->id) }}">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                                    Mark as Completed
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $bookings->links() }}
        </div>
    @endif

</div>
@endsection
