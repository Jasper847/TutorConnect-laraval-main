@extends('layouts.dashboard')

@section('title', 'Tutor Study Materials')
@section('header', 'Uploaded Study Materials')
@section('subheader', 'Upload learning resources, practice problem sheets, and notes for your students')

@section('content')
<div class="space-y-6" x-data="{ uploadModal: false }">
    
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-900">Your Resource Library</h3>
            <p class="text-xs text-slate-500">{{ $materials->total() }} uploaded files available to confirmed students</p>
        </div>
        <button type="button" @click="uploadModal = true" class="bg-accent-600 hover:bg-accent-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Upload New Material</span>
        </button>
    </div>

    @if($materials->isEmpty())
        <div class="bg-white p-12 rounded-3xl border border-slate-200/80 text-center space-y-4 shadow-sm">
            <i class="fa-regular fa-folder-open text-4xl text-slate-300"></i>
            <h3 class="text-base font-bold text-slate-900">No study materials uploaded yet</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Upload practice worksheets, cheat sheets, or presentation slides to help your students learn.</p>
            <button type="button" @click="uploadModal = true" class="bg-brand-800 text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-sm">
                Upload File Now
            </button>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="p-5">Title & Resource</th>
                        <th class="p-5">Subject</th>
                        <th class="p-5">File Type & Size</th>
                        <th class="p-5">Uploaded Date</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($materials as $mat)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-5">
                                <div class="font-bold text-slate-900 text-sm">{{ $mat->title }}</div>
                                @if($mat->description)
                                    <div class="text-slate-500 text-[11px] mt-0.5 line-clamp-1">{{ $mat->description }}</div>
                                @endif
                            </td>
                            <td class="p-5">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold">
                                    {{ $mat->subject?->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="p-5 text-slate-600 font-medium">
                                {{ $mat->material_type }} <span class="text-slate-400">({{ $mat->formatted_size }})</span>
                            </td>
                            <td class="p-5 text-slate-500 font-medium">
                                {{ $mat->created_at->format('M d, Y') }}
                            </td>
                            <td class="p-5 text-right">
                                <form method="POST" action="{{ route('tutor.materials.destroy', $mat->id) }}" onsubmit="return confirm('Are you sure you want to delete this study material?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg text-xs" title="Delete Material">
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
            {{ $materials->links() }}
        </div>
    @endif

    <!-- Upload Modal -->
    <div x-show="uploadModal" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="uploadModal = false"></div>
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full relative z-10 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-lg font-bold text-slate-900">Upload Study Material</h3>
                <button type="button" @click="uploadModal = false" class="p-2 text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('tutor.materials.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Resource Title</label>
                    <input type="text" name="title" required placeholder="e.g. Calculus Derivatives Cheat Sheet"
                           class="w-full text-xs font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Subject</label>
                        <select name="subject_id" class="w-full text-xs font-medium px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none">
                            <option value="">General</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Resource Type</label>
                        <select name="material_type" class="w-full text-xs font-medium px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none">
                            <option value="PDF Document">PDF Document</option>
                            <option value="Lecture Slides">Lecture Slides (PPT/PDF)</option>
                            <option value="Practice Problems">Practice Worksheet</option>
                            <option value="Formula Sheet">Cheat Sheet</option>
                            <option value="ZIP Archive">Code/ZIP Bundle</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Description (Optional)</label>
                    <textarea name="description" rows="2" placeholder="Briefly describe what this document covers..."
                              class="w-full text-xs font-medium p-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Select File (Max 15MB)</label>
                    <input type="file" name="file" required class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-800 hover:file:bg-brand-100 cursor-pointer w-full">
                </div>

                <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="uploadModal = false" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">
                        Cancel
                    </button>
                    <button type="submit" class="bg-accent-600 hover:bg-accent-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow transition-all">
                        Upload File
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
