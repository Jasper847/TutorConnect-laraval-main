@extends('layouts.app')

@section('title', 'Manage All Bookings')
@section('header', 'Platform Bookings Audit')
@section('subheader', 'System-wide booking management, status audit, and administrative cancellation')

@section('content')
<div class="space-y-6">
    
    <!-- Filter & Search Bar -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
        
        <!-- Status Tabs -->
        <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 pb-3">
            <a href="{{ route('admin.bookings.index') }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ !request('status') ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ request('status') === 'pending' ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Pending ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ request('status') === 'confirmed' ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Confirmed ({{ $counts['confirmed'] }})
            </a>
            <a href="{{ route('admin.bookings.index', ['status' => 'completed']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ request('status') === 'completed' ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Completed ({{ $counts['completed'] }})
            </a>
            <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ request('status') === 'cancelled' ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Cancelled ({{ $counts['cancelled'] }})
            </a>
        </div>

        <!-- Date Range & Search Form -->
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                       class="w-full text-xs font-medium px-3 py-2 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                       class="w-full text-xs font-medium px-3 py-2 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Search Keyword</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Code, student, tutor..."
                       class="w-full text-xs font-medium px-3 py-2 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-primary-800 hover:bg-primary-900 text-white font-semibold text-xs py-2 rounded-xl shadow-sm">
                    Filter Bookings
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs">
                    Reset
                </a>
            </div>
        </form>

    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        @if($bookings->isEmpty())
            <p class="text-xs text-slate-400 py-12 text-center">No bookings found for the selected criteria.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-gray-100 text-slate-400 uppercase font-semibold">
                        <tr>
                            <th class="p-4">Code</th>
                            <th class="p-4">Student</th>
                            <th class="p-4">Tutor</th>
                            <th class="p-4">Subject</th>
                            <th class="p-4">Date & Time</th>
                            <th class="p-4">Amount</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-slate-50/60 transition-colors" x-data="{ cancelModal: false }">
                                <td class="p-4 font-mono font-bold text-slate-900">#{{ $booking->booking_code }}</td>
                                <td class="p-4 font-medium text-slate-800">{{ $booking->student->name }}</td>
                                <td class="p-4 font-medium text-slate-800">{{ $booking->tutor->name }}</td>
                                <td class="p-4 font-semibold text-primary-800">{{ $booking->subject }}</td>
                                <td class="p-4 text-slate-500">
                                    {{ $booking->booking_date->format('M d, Y') }} at {{ date('g:i A', strtotime($booking->start_time)) }}
                                </td>
                                <td class="p-4 font-bold text-slate-900">PKR {{ number_format($booking->total_amount, 0) }}</td>
                                <td class="p-4">
                                    <x-booking-badge :status="$booking->status" />
                                </td>
                                <td class="p-4 text-right space-x-1.5">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700">
                                        Details
                                    </a>

                                    @if(in_array($booking->status, ['pending', 'confirmed']))
                                        <button type="button" @click="cancelModal = true" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600">
                                            Force Cancel
                                        </button>
                                    @endif

                                    <!-- Force Cancel Modal -->
                                    <div x-show="cancelModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 text-left">
                                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="cancelModal = false"></div>
                                        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-2xl max-w-md w-full relative z-10 space-y-4">
                                            <h3 class="text-base font-bold font-heading text-slate-900">Administrative Cancel Booking #{{ $booking->booking_code }}</h3>
                                            <p class="text-xs text-slate-500">Please provide an administrative reason for cancellation.</p>

                                            <form method="POST" action="{{ route('admin.bookings.cancel', $booking->id) }}" class="space-y-4">
                                                @csrf
                                                <textarea name="reason" rows="3" required placeholder="Reason for administrative cancellation..."
                                                          class="w-full text-xs font-medium p-3 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none"></textarea>

                                                <div class="flex justify-end gap-2 pt-2">
                                                    <button type="button" @click="cancelModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600">Close</button>
                                                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow">Force Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
