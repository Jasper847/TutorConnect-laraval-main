@extends('layouts.app')

@section('title', 'Edit Tutor Profile')
@section('header', 'Tutor Profile & Teaching Rates')
@section('subheader', 'Update your public listing, subjects, qualifications, and hourly rate')

@section('content')
<div class="max-w-4xl">
    
    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
        
        <form method="POST" action="{{ route('tutor.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Profile Photo Upload -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 pb-6 border-b border-gray-100">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-2xl object-cover ring-4 ring-gray-100 shadow-sm">
                <div class="space-y-1.5 flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Profile Photo</label>
                    <input type="file" name="photo" accept="image/*" 
                           class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-800 hover:file:bg-primary-100 cursor-pointer">
                    <p class="text-[11px] text-slate-400">Upload portrait photo (JPG, PNG, WebP — max 2MB). Stored securely.</p>
                    @error('photo')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Basic Details (Name, Location, Phone) -->
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
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Location / City</label>
                    <input type="text" name="location" value="{{ old('location', $profile->location ?: $user->city) }}" required placeholder="e.g. Lahore, Karachi, Islamabad"
                           class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border @error('location') border-rose-300 bg-rose-50/20 @else border-gray-200 bg-slate-50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                    @error('location')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+92 300 1234567"
                           class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                    @error('phone')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Rates, Experience & Status -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Hourly Rate (PKR)</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs font-bold text-slate-400">PKR</span>
                        <input type="number" name="hourly_rate" value="{{ old('hourly_rate', (int)$profile->hourly_rate) }}" min="500" max="10000" step="50" required
                               class="w-full text-xs sm:text-sm font-medium pl-14 pr-4 py-2.5 rounded-xl border @error('hourly_rate') border-rose-300 bg-rose-50/20 @else border-gray-200 bg-slate-50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Min PKR 500 — Max PKR 10,000</p>
                    @error('hourly_rate')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Experience (Years)</label>
                    <input type="number" name="experience_years" value="{{ old('experience_years', $profile->experience_years) }}" min="0" max="50" required
                           class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border @error('experience_years') border-rose-300 bg-rose-50/20 @else border-gray-200 bg-slate-50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                    @error('experience_years')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Accepting New Students</label>
                    <select name="is_available" class="w-full text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800">
                        <option value="1" {{ old('is_available', $profile->is_available) ? 'selected' : '' }}>Yes, Available for Bookings</option>
                        <option value="0" {{ !old('is_available', $profile->is_available) ? 'selected' : '' }}>No, Currently Fully Booked</option>
                    </select>
                </div>
            </div>

            <!-- Subjects Taught (Checkbox Multi-Select) -->
            <div class="pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Subjects Taught (Select all that apply)</label>
                    @error('subjects')
                        <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 p-4 rounded-xl border border-gray-100 bg-slate-50/50">
                    @foreach($availableSubjects as $subj)
                        <label class="flex items-center gap-2 p-2.5 rounded-lg bg-white border border-gray-100 hover:border-primary-800/30 cursor-pointer transition-colors shadow-xs">
                            <input type="checkbox" name="subjects[]" value="{{ $subj }}" 
                                   {{ in_array($subj, (array)old('subjects', $currentSubjects)) ? 'checked' : '' }}
                                   class="rounded text-primary-800 focus:ring-primary-800 h-4 w-4 border-gray-300">
                            <span class="text-xs font-semibold text-slate-700">{{ $subj }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Education (Textarea) -->
            <div class="pt-4 border-t border-gray-100">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Education & Degrees (Textarea)</label>
                <textarea name="education" rows="3" required placeholder="List your degrees, institutions, and certifications (e.g. M.Sc. in Mathematics from NUST, B.Sc. from Punjab University, Certified Cambridge Tutor)..."
                          class="w-full text-xs sm:text-sm font-medium p-4 rounded-xl border @error('education') border-rose-300 bg-rose-50/20 @else border-gray-200 bg-slate-50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800 leading-relaxed">{{ old('education', $profile->education) }}</textarea>
                @error('education')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bio (Textarea with 50 chars validation) -->
            <div class="pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Biography & Teaching Methodology</label>
                    <span class="text-[11px] text-slate-400">Minimum 50 characters required</span>
                </div>
                <textarea name="bio" rows="5" required minlength="50" placeholder="Introduce yourself to prospective students. Describe your teaching philosophy, past student success stories, exam preparation methodologies, and what students will gain..."
                          class="w-full text-xs sm:text-sm font-medium p-4 rounded-xl border @error('bio') border-rose-300 bg-rose-50/20 @else border-gray-200 bg-slate-50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-800/20 focus:border-primary-800 leading-relaxed">{{ old('bio', $profile->bio) }}</textarea>
                @error('bio')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('tutor.dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs px-8 py-3 rounded-xl shadow-md shadow-emerald-600/20 hover:shadow-lg transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Tutor Profile</span>
                </button>
            </div>
        </form>

    </div>

</div>
@endsection
