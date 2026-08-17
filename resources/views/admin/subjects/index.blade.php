@extends('layouts.dashboard')

@section('title', 'Admin Subjects Catalog')
@section('header', 'Subjects & Categories Taxonomy')
@section('subheader', 'Manage subject disciplines, icons, and tutor specializations')

@section('content')
<div class="space-y-6" x-data="{ newSubjectModal: false, editSubjectModal: false, editSubject: {} }">
    
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-900">Active Subjects ({{ $subjects->count() }})</h3>
            <p class="text-xs text-slate-500">Categories available for search and tutor profile tagging</p>
        </div>
        <button type="button" @click="newSubjectModal = true" class="bg-brand-800 hover:bg-brand-900 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Subject</span>
        </button>
    </div>

    <!-- Subjects Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($subjects as $subj)
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-4">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-800 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700">
                            {{ $subj->tutors_count }} Tutors
                        </span>
                    </div>
                    <h4 class="text-base font-bold text-slate-900">{{ $subj->name }}</h4>
                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $subj->description ?: 'No description provided.' }}</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <button type="button" @click="editSubject = { id: {{ $subj->id }}, name: '{{ addslashes($subj->name) }}', description: '{{ addslashes($subj->description ?? '') }}' }; editSubjectModal = true" 
                            class="text-xs font-bold text-brand-800 hover:underline">
                        Edit Details
                    </button>

                    <form method="POST" action="{{ route('admin.subjects.destroy', $subj->id) }}" onsubmit="return confirm('Delete this subject category?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg text-xs" title="Delete Subject">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Create Subject Modal -->
    <div x-show="newSubjectModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="newSubjectModal = false"></div>
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-2xl max-w-md w-full relative z-10 space-y-4">
            <h3 class="text-lg font-bold text-slate-900">Add Subject Category</h3>
            <form method="POST" action="{{ route('admin.subjects.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Subject Name</label>
                    <input type="text" name="name" required placeholder="e.g. Organic Chemistry"
                           class="w-full text-xs font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Description</label>
                    <textarea name="description" rows="2" placeholder="Brief topics summary..."
                              class="w-full text-xs font-medium p-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="newSubjectModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button>
                    <button type="submit" class="bg-brand-800 text-white font-bold text-xs px-5 py-2.5 rounded-xl">Create Subject</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Subject Modal -->
    <div x-show="editSubjectModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editSubjectModal = false"></div>
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-2xl max-w-md w-full relative z-10 space-y-4">
            <h3 class="text-lg font-bold text-slate-900">Edit Subject Category</h3>
            <form method="POST" :action="'/admin/subjects/' + editSubject.id" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Subject Name</label>
                    <input type="text" name="name" x-model="editSubject.name" required
                           class="w-full text-xs font-medium px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Description</label>
                    <textarea name="description" x-model="editSubject.description" rows="2"
                              class="w-full text-xs font-medium p-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="editSubjectModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button>
                    <button type="submit" class="bg-brand-800 text-white font-bold text-xs px-5 py-2.5 rounded-xl">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
