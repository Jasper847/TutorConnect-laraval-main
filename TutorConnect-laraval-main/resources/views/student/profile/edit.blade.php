@extends('layouts.dashboard')

@section('title', 'Edit Student Profile')
@section('header', 'My Profile')
@section('subheader', 'Update your personal details, academic grade, and learning goals')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
        <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Avatar Preview & Upload -->
            <div class="flex items-center gap-6">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-3xl object-cover ring-4 ring-slate-100 shadow-md">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Profile Photo</label>
                    <input type="file" name="avatar" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-800 hover:file:bg-brand-100 cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">PNG, JPG or WebP up to 2MB</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                </div>

                <!-- Email (Readonly) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Email Address</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+92 300..."
                           class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                </div>

                <!-- City -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">City / Location</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="e.g. Islamabad"
                           class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                </div>
            </div>

            <!-- Academic Info -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h4 class="text-sm font-bold text-slate-900">Academic Background</h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Current Grade / Level</label>
                        <input type="text" name="grade_level" value="{{ old('grade_level', $user->studentProfile->grade_level ?? '') }}" placeholder="e.g. A-Levels / Grade 11"
                               class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">School / College</label>
                        <input type="text" name="institution" value="{{ old('institution', $user->studentProfile->institution ?? '') }}" placeholder="e.g. Cambridge High School"
                               class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Learning Goals & Focus Subjects</label>
                    <textarea name="learning_goals" rows="3" placeholder="Tell your tutors about your upcoming test dates, weak topics, and what you aim to achieve..."
                              class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">{{ old('learning_goals', $user->studentProfile->learning_goals ?? '') }}</textarea>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-brand-800 hover:bg-brand-900 text-white font-bold text-sm px-7 py-3 rounded-xl shadow-md transition-all">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
