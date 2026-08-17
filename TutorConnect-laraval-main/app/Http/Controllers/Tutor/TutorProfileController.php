<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\Review;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TutorProfileController extends Controller
{
    /**
     * Calculate profile completion percentage.
     */
    protected function calculateCompletion(User $user, ?TutorProfile $profile): int
    {
        if (!$profile) {
            return 10;
        }

        $points = 0;
        $total = 7;

        if (!empty($user->profile_photo)) $points++;
        if (!empty($profile->bio) && strlen($profile->bio) >= 50) $points++;
        if (!empty($profile->subjects) && count($profile->subjects) > 0) $points++;
        if ($profile->hourly_rate >= 500) $points++;
        if (!empty($profile->education)) $points++;
        if (!empty($profile->location) || !empty($user->city)) $points++;
        if ($user->availabilitySlots()->exists()) $points++;

        return (int) round(($points / $total) * 100);
    }

    /**
     * 1. Dashboard: Home page for tutor.
     */
    public function dashboard(): View
    {
        $tutor = Auth::user();
        $profile = $tutor->tutorProfile ?: $tutor->tutorProfile()->create([
            'hourly_rate' => 1500.00,
            'experience_years' => 1,
            'is_available' => true,
        ]);

        $totalBookings = Booking::where('tutor_id', $tutor->id)->count();
        $pendingBookingsCount = Booking::where('tutor_id', $tutor->id)->where('status', 'pending')->count();
        $completedSessions = Booking::where('tutor_id', $tutor->id)->where('status', 'completed')->count();
        $avgRating = $profile->avg_rating ?? ($profile->rating_cache ?? 5.0);

        // Upcoming 5 sessions (confirmed & pending)
        $upcomingBookings = Booking::where('tutor_id', $tutor->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->with('student')
            ->orderBy('booking_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        // Recent 3 reviews
        $recentReviews = Review::where('tutor_id', $tutor->id)
            ->with('student')
            ->latest()
            ->take(3)
            ->get();

        // Profile completion percentage
        $completionPercentage = $this->calculateCompletion($tutor, $profile);

        $stats = [
            'total_bookings' => $totalBookings,
            'pending_bookings' => $pendingBookingsCount,
            'completed_sessions' => $completedSessions,
            'avg_rating' => $avgRating,
            'reviews_count' => $profile->reviews_count ?? Review::where('tutor_id', $tutor->id)->count(),
        ];

        return view('tutor.dashboard', compact(
            'tutor',
            'profile',
            'stats',
            'upcomingBookings',
            'recentReviews',
            'completionPercentage'
        ));
    }

    /**
     * 2. Edit Profile Form.
     */
    public function editProfile(): View
    {
        $user = Auth::user();
        $profile = $user->tutorProfile ?: $user->tutorProfile()->create([
            'hourly_rate' => 1500.00,
            'experience_years' => 1,
            'is_available' => true,
        ]);

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

        $currentSubjects = is_array($profile->subjects) ? $profile->subjects : ($profile->subjects ? json_decode($profile->subjects, true) : []);

        return view('tutor.profile.edit', compact('user', 'profile', 'availableSubjects', 'currentSubjects'));
    }

    /**
     * Update Profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->tutorProfile ?: new TutorProfile(['user_id' => $user->id]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string', 'min:50', 'max:3000'],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['string'],
            'hourly_rate' => ['required', 'numeric', 'min:500', 'max:10000'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'education' => ['required', 'string', 'max:2000'],
            'location' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2MB max
        ]);

        // Handle Photo Upload (store in storage/app/public/photos)
        if ($request->hasFile('photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $user->profile_photo = $path;
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->city = $request->location;
        $user->save();

        $profile->fill([
            'user_id' => $user->id,
            'headline' => $request->headline ?: ($request->subjects[0] ?? 'Experienced') . ' Specialist',
            'bio' => $request->bio,
            'subjects' => array_values($request->subjects),
            'hourly_rate' => $request->hourly_rate,
            'experience_years' => $request->experience_years,
            'education' => $request->education,
            'location' => $request->location,
            'is_available' => $request->boolean('is_available', true),
        ]);
        $profile->save();

        return redirect()->route('tutor.profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * 3. Set Availability (Weekly Schedule Matrix).
     */
    public function setAvailability(): View
    {
        $tutor = Auth::user();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $existingSlots = AvailabilitySlot::where('tutor_id', $tutor->id)->get()->keyBy(function ($item) {
            return strtolower($item->day_of_week);
        });

        return view('tutor.availability.index', compact('days', 'existingSlots'));
    }

    /**
     * Update Availability Slots.
     */
    public function updateAvailability(Request $request): RedirectResponse
    {
        $tutor = Auth::user();
        $daysInput = $request->input('days', []);

        foreach ($daysInput as $dayName => $config) {
            $isAvailable = isset($config['available']) && ($config['available'] == '1' || $config['available'] == 'yes');
            $dayKey = strtolower($dayName);

            if ($isAvailable) {
                $startTime = $config['start_time'] ?? '09:00';
                $endTime = $config['end_time'] ?? '17:00';

                AvailabilitySlot::updateOrCreate(
                    ['tutor_id' => $tutor->id, 'day_of_week' => $dayKey],
                    [
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'is_booked' => false,
                    ]
                );
            } else {
                AvailabilitySlot::where('tutor_id', $tutor->id)
                    ->where('day_of_week', $dayKey)
                    ->delete();
            }
        }

        return redirect()->route('tutor.availability.index')->with('success', 'Weekly availability schedule saved successfully!');
    }

    /**
     * 4. My Bookings with Filter by Status.
     */
    public function myBookings(Request $request): View
    {
        $tutor = Auth::user();
        $status = $request->get('status', 'all');

        $query = Booking::where('tutor_id', $tutor->id)
            ->with(['student', 'payment'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc');

        if (in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(10)->withQueryString();

        $counts = [
            'all' => Booking::where('tutor_id', $tutor->id)->count(),
            'pending' => Booking::where('tutor_id', $tutor->id)->where('status', 'pending')->count(),
            'confirmed' => Booking::where('tutor_id', $tutor->id)->where('status', 'confirmed')->count(),
            'completed' => Booking::where('tutor_id', $tutor->id)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('tutor_id', $tutor->id)->where('status', 'cancelled')->count(),
        ];

        return view('tutor.bookings.index', compact('bookings', 'status', 'counts'));
    }

    /**
     * Confirm a Booking.
     */
    public function confirmBooking(Booking $booking): RedirectResponse
    {
        if ($booking->tutor_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status === 'pending') {
            $booking->update(['status' => 'confirmed']);
        }

        return back()->with('success', 'Booking #' . $booking->booking_code . ' confirmed successfully!');
    }

    /**
     * Mark Booking Complete.
     */
    public function completeBooking(Booking $booking): RedirectResponse
    {
        if ($booking->tutor_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status === 'confirmed') {
            $booking->update(['status' => 'completed']);
        }

        return back()->with('success', 'Session marked as completed!');
    }

    /**
     * Cancel a Booking.
     */
    public function cancelBooking(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->tutor_id !== Auth::id()) {
            abort(403);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->input('cancellation_reason', 'Cancelled by tutor.'),
        ]);

        return back()->with('success', 'Booking cancelled.');
    }

    /**
     * 5. My Reviews.
     */
    public function myReviews(): View
    {
        $tutor = Auth::user();
        $profile = $tutor->tutorProfile;

        $reviews = Review::where('tutor_id', $tutor->id)
            ->with(['student', 'booking'])
            ->latest()
            ->paginate(10);

        $totalReviews = Review::where('tutor_id', $tutor->id)->count();

        // 5 to 1 star breakdown
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = Review::where('tutor_id', $tutor->id)->where('rating', $i)->count();
        }

        return view('tutor.reviews.index', compact('reviews', 'profile', 'totalReviews', 'ratingDistribution'));
    }
}
