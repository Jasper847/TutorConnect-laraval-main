<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $studentId = Auth::id();

        // Get all tutors the student has conversations with or booked with
        $tutorIds = Message::where('sender_id', $studentId)
            ->pluck('receiver_id')
            ->merge(Message::where('receiver_id', $studentId)->pluck('sender_id'))
            ->merge(Booking::where('student_id', $studentId)->pluck('tutor_id'))
            ->unique()
            ->filter();

        $tutors = User::whereIn('id', $tutorIds)
            ->with('tutorProfile')
            ->get()
            ->map(function ($tutor) use ($studentId) {
                $lastMsg = Message::where(function ($q) use ($studentId, $tutor) {
                    $q->where('sender_id', $studentId)->where('receiver_id', $tutor->id);
                })->orWhere(function ($q) use ($studentId, $tutor) {
                    $q->where('sender_id', $tutor->id)->where('receiver_id', $studentId);
                })->latest()->first();

                $unreadCount = Message::where('sender_id', $tutor->id)
                    ->where('receiver_id', $studentId)
                    ->where('is_read', false)
                    ->count();

                $tutor->last_message = $lastMsg;
                $tutor->unread_count = $unreadCount;
                return $tutor;
            });

        return view('student.messages.index', compact('tutors'));
    }

    public function show($tutorId)
    {
        $studentId = Auth::id();
        $tutor = User::where('role', 'tutor')->with('tutorProfile')->findOrFail($tutorId);

        // Mark unread messages from this tutor as read
        Message::where('sender_id', $tutorId)
            ->where('receiver_id', $studentId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($q) use ($studentId, $tutorId) {
            $q->where('sender_id', $studentId)->where('receiver_id', $tutorId);
        })->orWhere(function ($q) use ($studentId, $tutorId) {
            $q->where('sender_id', $tutorId)->where('receiver_id', $studentId);
        })->orderBy('created_at', 'asc')->get();

        if (request()->wantsJson()) {
            return response()->json(['messages' => $messages]);
        }

        // Tutors list for sidebar
        $tutorIds = Message::where('sender_id', $studentId)
            ->pluck('receiver_id')
            ->merge(Message::where('receiver_id', $studentId)->pluck('sender_id'))
            ->merge(Booking::where('student_id', $studentId)->pluck('tutor_id'))
            ->unique();

        $tutors = User::whereIn('id', $tutorIds)->with('tutorProfile')->get();

        return view('student.messages.show', compact('tutor', 'messages', 'tutors'));
    }

    public function send(Request $request, $tutorId)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $tutorId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', 'Message sent.');
    }
}
