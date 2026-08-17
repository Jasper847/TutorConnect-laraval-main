<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Mail\BookingCancelled;
use App\Mail\BookingRequested;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user();
        $tab = $request->get('tab', 'all');

        $query = Booking::where('student_id', $student->id)
            ->with(['tutor.tutorProfile', 'payment', 'review']);

        if ($tab === 'upcoming') {
            $query->whereIn('status', ['confirmed', 'pending'])->where('booking_date', '>=', now()->toDateString());
        } elseif ($tab === 'completed') {
            $query->where('status', 'completed');
        } elseif ($tab === 'cancelled') {
            $query->where('status', 'cancelled');
        } elseif ($tab === 'pending') {
            $query->where('status', 'pending');
        }

        $bookings = $query->orderBy('booking_date', 'desc')->orderBy('start_time', 'desc')->paginate(10)->withQueryString();

        $counts = [
            'all' => Booking::where('student_id', $student->id)->count(),
            'upcoming' => Booking::where('student_id', $student->id)->whereIn('status', ['confirmed', 'pending'])->where('booking_date', '>=', now()->toDateString())->count(),
            'completed' => Booking::where('student_id', $student->id)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('student_id', $student->id)->where('status', 'cancelled')->count(),
        ];

        return view('student.bookings.index', compact('bookings', 'counts', 'tab'));
    }

    public function create($tutorId)
    {
        $tutor = User::where('role', 'tutor')
            ->where('is_active', true)
            ->with(['tutorProfile.availabilities'])
            ->findOrFail($tutorId);

        $subjects = Subject::orderBy('name')->get();

        return view('student.bookings.create', compact('tutor', 'subjects'));
    }

    public function store(Request $request, $tutorId)
    {
        $tutor = User::where('role', 'tutor')->with('tutorProfile')->findOrFail($tutorId);

        $request->validate([
            'subject' => ['nullable', 'string', 'max:255'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required'],
            'duration_hours' => ['required', 'numeric', 'min:0.5', 'max:4'],
            'mode' => ['required', 'in:online,in_person'],
            'student_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $duration = (float) $request->duration_hours;
        $startTime = date('H:i:s', strtotime($request->start_time));
        $endTime = date('H:i:s', strtotime($request->start_time . " + " . ($duration * 60) . " minutes"));
        $hourlyRate = $tutor->tutorProfile->hourly_rate ?? 1500.00;
        $totalAmount = $hourlyRate * $duration;

        $subjectName = $request->subject ?: ($tutor->tutorProfile->subjects[0] ?? 'General Tutoring');

        $booking = Booking::create([
            'booking_code' => 'TC-' . strtoupper(Str::random(6)),
            'student_id' => Auth::id(),
            'tutor_id' => $tutor->id,
            'subject' => $subjectName,
            'booking_date' => $request->booking_date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'mode' => $request->mode,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'notes' => $request->student_notes,
        ]);

        // Send Email Notification to Tutor
        try {
            Mail::to($tutor->email)->send(new BookingRequested($booking));
        } catch (\Exception $e) {
        }

        // Redirect to Sandbox Payment Checkout
        return redirect()->route('student.payment.checkout', $booking->id);
    }

    public function show($id)
    {
        $booking = Booking::where('student_id', Auth::id())
            ->with(['tutor.tutorProfile', 'payment', 'review'])
            ->findOrFail($id);

        return view('student.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('student_id', Auth::id())->findOrFail($id);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This booking cannot be cancelled in its current status.');
        }

        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:500'],
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        // Notify Tutor
        try {
            Mail::to($booking->tutor->email)->send(new BookingCancelled($booking, $request->cancellation_reason));
        } catch (\Exception $e) {
        }

        return back()->with('success', 'Booking has been cancelled.');
    }
}
