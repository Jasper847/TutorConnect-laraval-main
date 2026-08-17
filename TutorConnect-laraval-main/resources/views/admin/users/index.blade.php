@extends('layouts.dashboard')

@section('title', 'Admin User Management')
@section('header', 'User Accounts Management')
@section('subheader', 'Search, filter, activate/deactivate, and moderate all platform users')

@section('content')
<div class="space-y-6">
    
    <!-- Search & Filters -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex-1 flex flex-wrap items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, email, or city..."
                       class="w-full text-xs font-medium pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
            </div>

            <select name="role" class="text-xs font-medium px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none">
                <option value="">All Roles</option>
                <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Students</option>
                <option value="tutor" {{ request('role') === 'tutor' ? 'selected' : '' }}>Tutors</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admins</option>
            </select>

            <select name="status" class="text-xs font-medium px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Deactivated Only</option>
            </select>

            <button type="submit" class="bg-brand-800 hover:bg-brand-900 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-sm">
                Filter
            </button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="p-5">User</th>
                    <th class="p-5">Role</th>
                    <th class="p-5">Status</th>
                    <th class="p-5">Location</th>
                    <th class="p-5">Joined Date</th>
                    <th class="p-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($users as $user)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-5 flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover">
                            <div>
                                <p class="font-bold text-slate-900 text-sm">{{ $user->name }}</p>
                                <p class="text-slate-400 text-[11px]">{{ $user->email }}</p>
                            </div>
                        </td>
                        <td class="p-5">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold {{ $user->role === 'tutor' ? 'bg-purple-100 text-purple-800' : ($user->role === 'admin' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="p-5">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 text-emerald-700 font-bold text-[11px]">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-rose-700 font-bold text-[11px]">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="p-5 text-slate-600 font-medium">{{ $user->city ?: '—' }}</td>
                        <td class="p-5 text-slate-500 font-medium">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="p-5 text-right space-x-2">
                            @if(!$user->isAdmin())
                                <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-lg font-bold text-[11px] {{ $user->is_active ? 'bg-amber-50 hover:bg-amber-100 text-amber-800' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800' }}" title="{{ $user->is_active ? 'Deactivate User' : 'Activate User' }}">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to permanently delete this user account?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg text-xs" title="Delete User">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-[10px] text-slate-400 font-semibold italic">Protected Admin</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pt-4">
        {{ $users->links() }}
    </div>

</div>
@endsection
