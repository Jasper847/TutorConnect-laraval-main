<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Mail\BookingRequested;
use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\Review;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * 1. Dashboard: Student home page.
     */
    public function dashboard(): View
    {
        $student = Auth::user();
        $profile = $student->studentProfile ?: $student->studentProfile()->create();

        $totalBookings = Booking::where('student_id', $student->id)->count();
        
        $upcomingSessions = Booking::where('student_id', $student->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('booking_date', '>=', now()->toDateString())
            ->count();

        $tutorsWorkedWith = Booking::where('student_id', $student->id)
            ->where('status', 'completed')
            ->distinct('tutor_id')
            ->count('tutor_id');

        // Upcoming 3 bookings
        $upcomingBookings = Booking::where('student_id', $student->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('booking_date', '>=', now()->toDateString())
            ->with('tutor.tutorProfile')
            ->orderBy('booking_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(3)
            ->get();

        // Recommended tutors based on student's subjects_needed
        $neededSubjects = is_array($profile->subjects_needed) ? $profile->subjects_needed : [];

        $recommendedQuery = TutorProfile::with('user')
            ->where('is_available', true)
            ->whereHas('user', fn($q) => $q->where('is_active', true));

        if (!empty($neededSubjects)) {
            $recommendedQuery->where(function ($q) use ($neededSubjects) {
                foreach ($neededSubjects as $subj) {
                    $q->orWhere('subjects', 'like', '%' . $subj . '%');
                }
            });
        }

        $recommendedTutors = $recommendedQuery->orderBy('is_verified', 'desc')
            ->orderBy('avg_rating', 'desc')
            ->take(4)
            ->get();

        // Fallback if no matching tutors
        if ($recommendedTutors->isEmpty()) {
            $recommendedTutors = TutorProfile::with('user')
                ->where('is_available', true)
                ->orderBy('avg_rating', 'desc')
                ->take(4)
                ->get();
        }

        $stats = [
            'total_bookings' => $totalBookings,
            'upcoming_sessions' => $upcomingSessions,
            'tutors_worked_with' => $tutorsWorkedWith,
            'completed_sessions' => Booking::where('student_id', $student->id)->where('status', 'completed')->count(),
        ];

        return view('student.dashboard', compact('student', 'profile', 'stats', 'upcomingBookings', 'recommendedTutors'));
    }

    /**
     * 2. Edit Profile.
     */
    public function editProfile(): View
    {
        $user = Auth::user();
        $profile = $user->studentProfile ?: $user->studentProfile()->create();

        $gradeLevels = ['Matric', 'Intermediate', 'Bachelors', 'Masters', 'Other'];
        
        $availableSubjects = [
            'Math',
            'Physics',
            'English',
            'Chemistry',
            'Computer Science',
            'Biology',
            'Urdu',
            'Islamiat',
            'History',
            'Geography',
        ];

        $currentSubjects = is_array($profile->subjects_needed) ? $profile->subjects_needed : ($profile->subjects_needed ? json_decode($profile->subjects_needed, true) : []);

        return view('student.profile.edit', compact('user', 'profile', 'gradeLevels', 'availableSubjects', 'currentSubjects'));
    }

    /**
     * Update Profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->studentProfile ?: new StudentProfile(['user_id' => $user->id]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'in:Matric,Intermediate,Bachelors,Masters,Other'],
            'subjects_needed' => ['nullable', 'array'],
            'subjects_needed.*' => ['string'],
            'about' => ['nullable', 'string', 'max:2000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'city' => $request->city,
        ]);

        $profile->fill([
            'user_id' => $user->id,
            'grade_level' => $request->grade_level,
            'subjects_needed' => array_values($request->input('subjects_needed', [])),
            'about' => $request->about,
        ]);
        $profile->save();

        return redirect()->route('student.profile.edit')->with('success', 'Your student profile has been updated!');
    }

    /**
     * 3. Search & Browse Tutors.
     */
    public function searchTutors(Request $request): View
    {
        $subjects = [
            'Math',
            'Physics',
            'English',
            'Chemistry',
            'Computer Science',
            'Biology',
            'Urdu',
            'Islamiat',
            'History',
            'Geography',
        ];

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $query = TutorProfile::with(['user', 'availabilities'])
            ->where('is_available', true)
            ->whereHas('user', fn($q) => $q->where('is_active', true));

        // Subject Filter
        if ($request->filled('subject')) {
            $subj = $request->subject;
            $query->where('subjects', 'like', '%' . $subj . '%');
        }

        // Keyword Search
        if ($request->filled('q')) {
            $searchTerm = '%' . $request->q . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('headline', 'like', $searchTerm)
                  ->orWhere('bio', 'like', $searchTerm)
                  ->orWhere('subjects', 'like', $searchTerm)
                  ->orWhere('education', 'like', $searchTerm)
                  ->orWhere('location', 'like', $searchTerm)
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', $searchTerm));
            });
        }

        // Price Filter
        if ($request->filled('min_price')) {
            $query->where('hourly_rate', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('hourly_rate', '<=', (float) $request->max_price);
        }

        // Availability Day Filter
        if ($request->filled('day')) {
            $dayLower = strtolower($request->day);
            $query->whereHas('availabilities', function ($q) use ($dayLower) {
                $q->where('day_of_week', $dayLower);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'recommended');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('hourly_rate', 'asc');
                break;
            case 'price_high':
                $query->orderBy('hourly_rate', 'desc');
                break;
            case 'rating':
                $query->orderBy('avg_rating', 'desc');
                break;
            default:
                $query->orderBy('is_verified', 'desc')->orderBy('avg_rating', 'desc');
                break;
        }

        $tutors = $query->paginate(9)->withQueryString();

        return view('student.tutors.index', compact('tutors', 'subjects', 'days'));
    }

    /**
     * 4. Tutor Detail Page.
     */
    public function tutorDetail($id): View
    {
        $tutor = User::where('role', 'tutor')
            ->where('is_active', true)
            ->with(['tutorProfile.availabilities'])
            ->findOrFail($id);

        $reviews = Review::where('tutor_id', $id)
            ->with('student')
            ->latest()
            ->paginate(6);

        $availableSlots = AvailabilitySlot::where('tutor_id', $id)->get();

        return view('student.tutors.show', compact('tutor', 'reviews', 'availableSlots'));
    }

    /**
     * 5. Book Tutor Creation Form.
     */
    public function bookTutor($tutorId): View
    {
        $tutor = User::where('role', 'tutor')
            ->where('is_active', true)
            ->with(['tutorProfile.availabilities'])
            ->findOrFail($tutorId);

        $profile = $tutor->tutorProfile;
        $tutorSubjects = is_array($profile->subjects) ? $profile->subjects : ($profile->subjects ? json_decode($profile->subjects, true) : []);
        $availableDays = AvailabilitySlot::where('tutor_id', $tutor->id)->pluck('day_of_week')->map(fn($d) => ucfirst($d))->unique()->values();

        return view('student.bookings.create', compact('tutor', 'profile', 'tutorSubjects', 'availableDays'));
    }

    /**
     * AJAX endpoint to load time slot details for a given day.
     */
    public function getTutorSlots(Request $request, $tutorId): JsonResponse
    {
        $dayOfWeek = strtolower($request->get('day'));
        
        $slot = AvailabilitySlot::where('tutor_id', $tutorId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$slot) {
            return response()->json(['available' => false, 'message' => 'Tutor is not available on this day.']);
        }

        return response()->json([
            'available' => true,
            'start_time' => date('H:i', strtotime($slot->start_time)),
            'end_time' => date('H:i', strtotime($slot->end_time)),
            'display' => date('g:i A', strtotime($slot->start_time)) . ' - ' . date('g:i A', strtotime($slot->end_time)),
        ]);
    }

    /**
     * Store Booking.
     */
    public function storeBooking(Request $request, $tutorId): RedirectResponse
    {
        $tutor = User::where('role', 'tutor')->with('tutorProfile')->findOrFail($tutorId);

        $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $profile = $tutor->tutorProfile;
        $hourlyRate = $profile ? $profile->hourly_rate : 1500.00;

        $startTime = date('H:i:s', strtotime($request->start_time));
        $endTime = date('H:i:s', strtotime($request->start_time . ' + 1 hour'));

        $booking = Booking::create([
            'booking_code' => 'TC-' . strtoupper(Str::random(6)),
            'student_id' => Auth::id(),
            'tutor_id' => $tutor->id,
            'subject' => $request->subject,
            'booking_date' => $request->booking_date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'mode' => 'online',
            'total_amount' => $hourlyRate,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        try {
            Mail::to($tutor->email)->send(new BookingRequested($booking));
        } catch (\Exception $e) {
        }

        return redirect()->route('student.payment.checkout', $booking->id)->with('success', 'Booking created! Please complete payment in sandbox mode.');
    }

    /**
     * 6. My Bookings with Filter.
     */
    public function myBookings(Request $request): View
    {
        $student = Auth::user();
        $status = $request->get('status', 'all');

        $query = Booking::where('student_id', $student->id)
            ->with(['tutor.tutorProfile', 'review', 'payment'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc');

        if (in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(10)->withQueryString();

        $counts = [
            'all' => Booking::where('student_id', $student->id)->count(),
            'pending' => Booking::where('student_id', $student->id)->where('status', 'pending')->count(),
            'confirmed' => Booking::where('student_id', $student->id)->where('status', 'confirmed')->count(),
            'completed' => Booking::where('student_id', $student->id)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('student_id', $student->id)->where('status', 'cancelled')->count(),
        ];

        return view('student.bookings.index', compact('bookings', 'status', 'counts'));
    }

    /**
     * 7. Leave Review Form (Only for Completed Bookings).
     */
    public function leaveReview($bookingId): View
    {
        $booking = Booking::where('student_id', Auth::id())
            ->where('status', 'completed')
            ->with('tutor.tutorProfile')
            ->findOrFail($bookingId);

        if ($booking->review()->exists()) {
            return redirect()->route('student.bookings.index')->with('info', 'You have already reviewed this tutoring session.');
        }

        return view('student.reviews.create', compact('booking'));
    }

    /**
     * Store Review.
     */
    public function storeReview(Request $request, $bookingId): RedirectResponse
    {
        $booking = Booking::where('student_id', Auth::id())
            ->where('status', 'completed')
            ->with('tutor.tutorProfile')
            ->findOrFail($bookingId);

        if ($booking->review()->exists()) {
            return redirect()->route('student.bookings.index')->with('error', 'Review has already been submitted for this session.');
        }

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:20', 'max:2000'],
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'student_id' => Auth::id(),
            'tutor_id' => $booking->tutor_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Tutor avg_rating is automatically recalculated via Review model booted hook

        return redirect()->route('student.bookings.index')->with('success', 'Thank you! Your verified review has been submitted and published.');
    }
}
