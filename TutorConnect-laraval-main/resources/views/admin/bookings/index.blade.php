@extends('layouts.dashboard')

@section('title', 'Admin Platform Bookings')
@section('header', 'All Platform Bookings')
@section('subheader', 'Audit all tutoring appointments, payment status, and session delivery')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Form -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex-1 flex flex-wrap items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by booking code, student, or tutor..."
                       class="w-full text-xs font-medium pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
            </div>

            <select name="status" class="text-xs font-medium px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <button type="submit" class="bg-brand-800 hover:bg-brand-900 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-sm">
                Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="p-5">Code</th>
                    <th class="p-5">Student</th>
                    <th class="p-5">Tutor</th>
                    <th class="p-5">Subject</th>
                    <th class="p-5">Date & Time</th>
                    <th class="p-5">Status</th>
                    <th class="p-5">Amount</th>
                    <th class="p-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($bookings as $booking)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-5 font-bold font-mono text-slate-900">#{{ $booking->booking_code }}</td>
                        <td class="p-5 font-bold text-slate-900">{{ $booking->student->name }}</td>
                        <td class="p-5 font-bold text-slate-900">{{ $booking->tutor->name }}</td>
                        <td class="p-5 text-slate-600 font-medium">{{ $booking->subject?->name ?? 'General' }}</td>
                        <td class="p-5 text-slate-500 font-medium">
                            {{ $booking->booking_date->format('M d, Y') }} at {{ date('g:i A', strtotime($booking->start_time)) }}
                        </td>
                        <td class="p-5">
                            @if($booking->status === 'confirmed')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Confirmed</span>
                            @elseif($booking->status === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Pending</span>
                            @elseif($booking->status === 'completed')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">Completed</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Cancelled</span>
                            @endif
                        </td>
                        <td class="p-5 font-extrabold text-slate-900">${{ number_format($booking->total_amount, 2) }}</td>
                        <td class="p-5 text-right">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-block bg-slate-100 hover:bg-slate-200 text-slate-800 text-[11px] font-bold px-3 py-1.5 rounded-lg transition-all">
                                Inspect
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pt-4">
        {{ $bookings->links() }}
    </div>

</div>
@endsection
