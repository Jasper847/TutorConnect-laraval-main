@extends('layouts.app')

@section('title', 'Edit Student Profile')
@section('header', 'Student Profile & Learning Goals')
@section('subheader', 'Update your academic level, subjects you need help with, and learning goals')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
        
        <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Avatar & Name Overview -->
            <div class="flex items-center gap-5 pb-6 border-b border-gray-100">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-gray-100">
                <div>
                    <h3 class="text-base font-bold font-heading text-slate-900">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-primary-50 text-primary-800 mt-1">Student Account</span>
                </div>
            </div>

            <!-- Basic Info (Name, City, Phone) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border @error('name') border-rose-300 bg-rose-50/20 @else border-gray-200 bg-slate-50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                    @error('name')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">City / Location</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="e.g. Lahore, Karachi"
                           class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+92 300 1234567"
                           class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                </div>
            </div>

            <!-- Grade Level (Dropdown: Matric, Intermediate, Bachelors, Masters, Other) -->
            <div class="pt-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Academic Grade Level</label>
                <select name="grade_level" required class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border @error('grade_level') border-rose-300 bg-rose-50/20 @else border-gray-200 bg-slate-50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                    <option value="">Select your current academic level</option>
                    @foreach($gradeLevels as $level)
                        <option value="{{ $level }}" {{ old('grade_level', $profile->grade_level) === $level ? 'selected' : '' }}>
                            {{ $level }}
                        </option>
                    @endforeach
                </select>
                @error('grade_level')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subjects Needed (Multi-select Checkboxes) -->
            <div class="pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Subjects Needed (Select all that apply)</label>
                    <span class="text-[11px] text-slate-400">Used to recommend relevant tutors</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 p-4 rounded-xl border border-gray-100 bg-slate-50/50">
                    @foreach($availableSubjects as $subj)
                        <label class="flex items-center gap-2 p-2.5 rounded-lg bg-white border border-gray-100 hover:border-primary-800/30 cursor-pointer transition-colors shadow-xs">
                            <input type="checkbox" name="subjects_needed[]" value="{{ $subj }}" 
                                   {{ in_array($subj, (array)old('subjects_needed', $currentSubjects)) ? 'checked' : '' }}
                                   class="rounded text-primary-800 focus:ring-primary-800 h-4 w-4 border-gray-300">
                            <span class="text-xs font-semibold text-slate-700">{{ $subj }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- About / Learning Goals (Textarea) -->
            <div class="pt-4 border-t border-gray-100">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">About Your Learning Goals & Test Preparation (Textarea)</label>
                <textarea name="about" rows="4" placeholder="Tell your tutors about your upcoming exams, areas where you need help, syllabus board (e.g. Cambridge, FBISE, BISE), and target scores..."
                          class="w-full text-xs sm:text-sm font-medium p-4 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800 leading-relaxed">{{ old('about', $profile->about) }}</textarea>
                @error('about')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('student.dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-primary-800 hover:bg-primary-900 text-white font-semibold text-xs px-8 py-3 rounded-xl shadow-md shadow-primary-800/20 hover:shadow-lg transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Student Profile</span>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
