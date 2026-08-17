@extends('layouts.dashboard')

@section('title', 'Admin Reviews Moderation')
@section('header', 'Platform Reviews Moderation')
@section('subheader', 'Audit student feedback and moderate inappropriate remarks')

@section('content')
<div class="space-y-6">
    
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="p-5">Student</th>
                    <th class="p-5">Tutor</th>
                    <th class="p-5">Rating</th>
                    <th class="p-5">Review Comment</th>
                    <th class="p-5">Date</th>
                    <th class="p-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($reviews as $review)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-5 font-bold text-slate-900">{{ $review->student->name }}</td>
                        <td class="p-5 font-bold text-slate-900">{{ $review->tutor->name }}</td>
                        <td class="p-5">
                            <div class="flex items-center text-amber-400 text-xs">
                                @for($i=1; $i<=5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td class="p-5 text-slate-600 font-medium max-w-sm">
                            <span class="line-clamp-2">"{{ $review->comment }}"</span>
                        </td>
                        <td class="p-5 text-slate-500">{{ $review->created_at->format('M d, Y') }}</td>
                        <td class="p-5 text-right">
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" onsubmit="return confirm('Delete this review? Tutor rating cache will be automatically updated.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg text-xs" title="Delete Review">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pt-4">
        {{ $reviews->links() }}
    </div>

</div>
@endsection
