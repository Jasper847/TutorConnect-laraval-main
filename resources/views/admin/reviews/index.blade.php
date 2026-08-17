@extends('layouts.app')

@section('title', 'Moderate Reviews')
@section('header', 'Review & Rating Moderation')
@section('subheader', 'Audit student feedback and delete inappropriate or abusive reviews')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Bar -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reviews.index') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold {{ !request('rating') ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                All Ratings
            </a>
            @for($star = 5; $star >= 1; $star--)
                <a href="{{ route('admin.reviews.index', ['rating' => $star]) }}" 
                   class="px-3 py-2 rounded-xl text-xs font-semibold flex items-center gap-1 {{ request('rating') == $star ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    <span>{{ $star }}</span>
                    <i class="fa-solid fa-star text-[10px] text-amber-400"></i>
                </a>
            @endfor
        </div>

        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex items-center gap-2">
            @if(request('rating'))
                <input type="hidden" name="rating" value="{{ request('rating') }}">
            @endif
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search review text..."
                       class="text-xs font-medium pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none w-56">
            </div>
            <button type="submit" class="bg-primary-800 hover:bg-primary-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm">
                Filter
            </button>
        </form>
    </div>

    <!-- Reviews Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        @if($reviews->isEmpty())
            <p class="text-xs text-slate-400 py-12 text-center">No student reviews found matching your search.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-gray-100 text-slate-400 uppercase font-semibold">
                        <tr>
                            <th class="p-4">Student</th>
                            <th class="p-4">Tutor</th>
                            <th class="p-4">Rating</th>
                            <th class="p-4">Review Comment</th>
                            <th class="p-4">Date</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($reviews as $rev)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $rev->student->avatar_url }}" alt="{{ $rev->student->name }}" class="w-8 h-8 rounded-lg object-cover">
                                        <span class="font-bold text-slate-900">{{ $rev->student->name }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="font-semibold text-slate-800">{{ $rev->tutor->name }}</span>
                                </td>
                                <td class="p-4">
                                    <div class="flex text-amber-400 text-xs">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td class="p-4 max-w-md">
                                    <p class="text-slate-600 leading-relaxed italic line-clamp-2">"{{ $rev->comment }}"</p>
                                </td>
                                <td class="p-4 text-slate-500 whitespace-nowrap">{{ $rev->created_at->format('M d, Y') }}</td>
                                <td class="p-4 text-right">
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $rev->id) }}" onsubmit="return confirm('Delete this review? The tutor rating will be automatically recalculated.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600">
                                            Delete Review
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
