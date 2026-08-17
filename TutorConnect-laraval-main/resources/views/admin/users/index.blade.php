@extends('layouts.app')

@section('title', 'Manage Users')
@section('header', 'User Management')
@section('subheader', 'Search, filter roles, inspect account activity, and manage access')

@section('content')
<div class="space-y-6">
    
    <!-- Role Filter & Search Bar -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        
        <!-- Role Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.users.index') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all {{ !request('role') ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                All ({{ $roleCounts['all'] }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'student']) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all {{ request('role') === 'student' ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Students ({{ $roleCounts['student'] }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'tutor']) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all {{ request('role') === 'tutor' ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Tutors ({{ $roleCounts['tutor'] }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all {{ request('role') === 'admin' ? 'bg-primary-800 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Admins ({{ $roleCounts['admin'] }})
            </a>
        </div>

        <!-- Search Input -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name or email..."
                       class="text-xs font-medium pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 w-64">
            </div>
            <button type="submit" class="bg-primary-800 hover:bg-primary-900 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm">
                Search
            </button>
        </form>

    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        @if($users->isEmpty())
            <p class="text-xs text-slate-400 py-12 text-center">No users found matching the selected criteria.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-gray-100 text-slate-400 uppercase font-semibold">
                        <tr>
                            <th class="p-4">User</th>
                            <th class="p-4">Role</th>
                            <th class="p-4">City / Location</th>
                            <th class="p-4">Joined Date</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-gray-100">
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $u->name }}</p>
                                            <p class="text-[11px] text-slate-400">{{ $u->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold {{ $u->role === 'tutor' ? 'bg-purple-100 text-purple-800' : ($u->role === 'admin' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst($u->role) }}
                                    </span>
                                </td>
                                <td class="p-4 text-slate-600">{{ $u->city ?: 'Not specified' }}</td>
                                <td class="p-4 text-slate-500">{{ $u->created_at->format('M d, Y') }}</td>
                                <td class="p-4">
                                    @if($u->is_active)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-600">
                                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right space-x-2">
                                    @if($u->id !== auth()->id())
                                        <!-- Activate / Deactivate Toggle -->
                                        <form method="POST" action="{{ route('admin.users.toggle', $u->id) }}" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $u->is_active ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' }}">
                                                {{ $u->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                        <!-- Delete User Form -->
                                        <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-slate-400 font-semibold italic">Current Admin</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
