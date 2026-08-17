<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $tutorId = Auth::id();

        $studentIds = Message::where('sender_id', $tutorId)
            ->pluck('receiver_id')
            ->merge(Message::where('receiver_id', $tutorId)->pluck('sender_id'))
            ->merge(Booking::where('tutor_id', $tutorId)->pluck('student_id'))
            ->unique()
            ->filter();

        $students = User::whereIn('id', $studentIds)
            ->with('studentProfile')
            ->get()
            ->map(function ($student) use ($tutorId) {
                $lastMsg = Message::where(function ($q) use ($tutorId, $student) {
                    $q->where('sender_id', $tutorId)->where('receiver_id', $student->id);
                })->orWhere(function ($q) use ($tutorId, $student) {
                    $q->where('sender_id', $student->id)->where('receiver_id', $tutorId);
                })->latest()->first();

                $unreadCount = Message::where('sender_id', $student->id)
                    ->where('receiver_id', $tutorId)
                    ->where('is_read', false)
                    ->count();

                $student->last_message = $lastMsg;
                $student->unread_count = $unreadCount;
                return $student;
            });

        return view('tutor.messages.index', compact('students'));
    }

    public function show($studentId)
    {
        $tutorId = Auth::id();
        $student = User::where('role', 'student')->with('studentProfile')->findOrFail($studentId);

        // Mark messages as read
        Message::where('sender_id', $studentId)
            ->where('receiver_id', $tutorId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($q) use ($tutorId, $studentId) {
            $q->where('sender_id', $tutorId)->where('receiver_id', $studentId);
        })->orWhere(function ($q) use ($tutorId, $studentId) {
            $q->where('sender_id', $studentId)->where('receiver_id', $tutorId);
        })->orderBy('created_at', 'asc')->get();

        if (request()->wantsJson()) {
            return response()->json(['messages' => $messages]);
        }

        $studentIds = Message::where('sender_id', $tutorId)
            ->pluck('receiver_id')
            ->merge(Message::where('receiver_id', $tutorId)->pluck('sender_id'))
            ->merge(Booking::where('tutor_id', $tutorId)->pluck('student_id'))
            ->unique();

        $students = User::whereIn('id', $studentIds)->with('studentProfile')->get();

        return view('tutor.messages.show', compact('student', 'messages', 'students'));
    }

    public function send(Request $request, $studentId)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $studentId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', 'Reply sent.');
    }
}
