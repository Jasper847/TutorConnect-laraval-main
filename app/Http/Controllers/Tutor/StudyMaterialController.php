<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\StudyMaterial;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyMaterialController extends Controller
{
    public function index()
    {
        $tutorId = Auth::id();
        $materials = StudyMaterial::where('tutor_id', $tutorId)
            ->with('subject')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $subjects = Auth::user()->tutorProfile->subjects;

        return view('tutor.materials.index', compact('materials', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'material_type' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,txt,png,jpg', 'max:15360'],
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $file->getClientOriginalName());
        $filePath = 'uploads/study_materials/' . $fileName;

        if (!file_exists(public_path('uploads/study_materials'))) {
            mkdir(public_path('uploads/study_materials'), 0777, true);
        }

        $file->move(public_path('uploads/study_materials'), $fileName);

        StudyMaterial::create([
            'tutor_id' => Auth::id(),
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'description' => $request->description,
            'material_type' => $request->material_type,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => filesize(public_path($filePath)),
        ]);

        return back()->with('success', 'Study material uploaded successfully!');
    }

    public function destroy($id)
    {
        $material = StudyMaterial::where('tutor_id', Auth::id())->findOrFail($id);

        $fullPath = public_path($material->file_path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $material->delete();

        return back()->with('success', 'Study material removed.');
    }
}
