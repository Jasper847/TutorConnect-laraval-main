@extends('layouts.dashboard')

@section('title', 'Edit Tutor Profile')
@section('header', 'Tutor Profile & Teaching Rates')
@section('subheader', 'Update your public listing, qualifications, subjects, and biography')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
        <form method="POST" action="{{ route('tutor.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Avatar -->
            <div class="flex items-center gap-6">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-3xl object-cover ring-4 ring-slate-100 shadow-md">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Profile Photo</label>
                    <input type="file" name="avatar" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-800 hover:file:bg-brand-100 cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Clear portrait photo recommended (PNG, JPG, WebP)</p>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+92 300..."
                           class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">City / Location</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="e.g. Islamabad"
                           class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                </div>
            </div>

            <!-- Professional Headline & Rates -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Professional Headline</label>
                    <input type="text" name="headline" value="{{ old('headline', $user->tutorProfile->headline ?? '') }}" required
                           placeholder="e.g. Senior Calculus & Physics Specialist | 8+ Years Experience"
                           class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Hourly Rate ($ USD)</label>
                        <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $user->tutorProfile->hourly_rate ?? 25.00) }}" min="5" max="500" step="0.5" required
                               class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Experience (Years)</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years', $user->tutorProfile->experience_years ?? 1) }}" min="0" max="50" required
                               class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Teaching Mode</label>
                        <select name="teaching_mode" class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                            <option value="both" {{ ($user->tutorProfile->teaching_mode ?? '') === 'both' ? 'selected' : '' }}>Both Online & In-Person</option>
                            <option value="online" {{ ($user->tutorProfile->teaching_mode ?? '') === 'online' ? 'selected' : '' }}>Online Only</option>
                            <option value="in_person" {{ ($user->tutorProfile->teaching_mode ?? '') === 'in_person' ? 'selected' : '' }}>In-Person Only</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Highest Degree / Qualification</label>
                        <input type="text" name="qualification" value="{{ old('qualification', $user->tutorProfile->qualification ?? '') }}" required placeholder="e.g. M.Sc. in Physics"
                               class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">University / College</label>
                        <input type="text" name="institution" value="{{ old('institution', $user->tutorProfile->institution ?? '') }}" placeholder="e.g. NUST / FAST"
                               class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">
                    </div>
                </div>
            </div>

            <!-- Subjects Taught -->
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Subjects Taught (Select all that apply)</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 p-4 rounded-2xl border border-slate-200 bg-slate-50/50">
                    @php
                        $assignedSubjectIds = $user->tutorProfile->subjects->pluck('id')->toArray();
                    @endphp
                    @foreach($subjects as $subj)
                        <label class="flex items-center gap-2 p-2 hover:bg-white rounded-xl text-xs font-medium cursor-pointer border border-transparent hover:border-slate-200 transition-all">
                            <input type="checkbox" name="subjects[]" value="{{ $subj->id }}" 
                                   {{ in_array($subj->id, $assignedSubjectIds) ? 'checked' : '' }}
                                   class="rounded text-brand-800 focus:ring-brand-800 h-4 w-4">
                            <span class="truncate">{{ $subj->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Biography -->
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Biography & Teaching Approach</label>
                <textarea name="bio" rows="5" required minlength="20" placeholder="Introduce yourself, describe your methodology, past exam results of students, and what students can expect..."
                          class="w-full text-sm font-medium p-4 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800">{{ old('bio', $user->tutorProfile->bio ?? '') }}</textarea>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-brand-800 hover:bg-brand-900 text-white font-bold text-sm px-8 py-3 rounded-xl shadow-md transition-all">
                    Save Profile & Rates
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
