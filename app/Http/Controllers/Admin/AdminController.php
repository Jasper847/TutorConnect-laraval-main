<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * 1. Admin Overview Dashboard.
     */
    public function dashboard(): View
    {
        $totalUsers = User::count();
        $totalTutors = User::where('role', 'tutor')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalBookings = Booking::count();
        
        $totalRevenuePkr = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_amount');
        if ($totalRevenuePkr == 0) {
            $totalRevenuePkr = Payment::where('status', 'paid')->sum('amount') * 280;
        }

        $pendingVerifications = TutorProfile::where('is_verified', false)->count();

        // Recent 10 bookings
        $recentBookings = Booking::with(['student', 'tutor.tutorProfile'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Bookings per month for the CSS/Alpine bar chart
        $months = [];
        $maxMonthCount = 1;
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('M');
            $yearMonth = $date->format('Y-m');
            
            $count = Booking::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            if ($count > $maxMonthCount) {
                $maxMonthCount = $count;
            }

            $months[] = [
                'label' => $monthKey,
                'count' => $count,
            ];
        }

        $stats = [
            'total_users' => $totalUsers,
            'total_tutors' => $totalTutors,
            'total_students' => $totalStudents,
            'total_bookings' => $totalBookings,
            'total_revenue' => $totalRevenuePkr,
            'pending_verifications' => $pendingVerifications,
        ];

        return view('admin.dashboard', compact('stats', 'recentBookings', 'months', 'maxMonthCount'));
    }

    /**
     * 2. User Management.
     */
    public function users(Request $request): View
    {
        $query = User::query();

        // Role filter
        if ($request->filled('role') && in_array($request->role, ['admin', 'tutor', 'student'])) {
            $query->where('role', $request->role);
        }

        // Search by name or email
        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('email', 'like', $term)
                  ->orWhere('city', 'like', $term);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        $roleCounts = [
            'all' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'tutor' => User::where('role', 'tutor')->count(),
            'student' => User::where('role', 'student')->count(),
        ];

        return view('admin.users.index', compact('users', 'roleCounts'));
    }

    /**
     * Toggle User Active Status.
     */
    public function toggleUserStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own admin account.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $msg = $user->is_active ? 'User activated successfully.' : 'User deactivated.';
        return back()->with('success', $msg);
    }

    /**
     * Delete a User.
     */
    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * 3. Tutor Management.
     */
    public function tutors(Request $request): View
    {
        $query = TutorProfile::with('user');

        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status === 'unverified') {
                $query->where('is_verified', false);
            }
        }

        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('headline', 'like', $term)
                  ->orWhere('subjects', 'like', $term)
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', $term)->orWhere('email', 'like', $term));
            });
        }

        $tutors = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('admin.tutors.index', compact('tutors'));
    }

    /**
     * Verify Tutor.
     */
    public function verifyTutor(TutorProfile $tutor): RedirectResponse
    {
        $tutor->update(['is_verified' => true]);
        return back()->with('success', 'Tutor verified successfully.');
    }

    /**
     * Unverify Tutor.
     */
    public function unverifyTutor(TutorProfile $tutor): RedirectResponse
    {
        $tutor->update(['is_verified' => false]);
        return back()->with('success', 'Tutor verification status removed.');
    }

    /**
     * 4. All Bookings Management.
     */
    public function bookings(Request $request): View
    {
        $query = Booking::with(['student', 'tutor.tutorProfile', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->where('booking_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('booking_date', '<=', $request->to_date);
        }

        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('booking_code', 'like', $term)
                  ->orWhere('subject', 'like', $term)
                  ->orWhereHas('student', fn($sq) => $sq->where('name', 'like', $term))
                  ->orWhereHas('tutor', fn($tq) => $tq->where('name', 'like', $term));
            });
        }

        $bookings = $query->orderBy('booking_date', 'desc')->orderBy('start_time', 'desc')->paginate(15)->withQueryString();

        $counts = [
            'all' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'counts'));
    }

    /**
     * Force Cancel a Booking.
     */
    public function cancelBooking(Request $request, Booking $booking): RedirectResponse
    {
        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->input('reason', 'Cancelled by Platform Administrator.'),
        ]);

        return back()->with('success', 'Booking #' . $booking->booking_code . ' cancelled by Admin.');
    }

    /**
     * 5. Review Moderation.
     */
    public function reviews(Request $request): View
    {
        $query = Review::with(['student', 'tutor', 'booking']);

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->rating);
        }

        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('comment', 'like', $term)
                  ->orWhereHas('student', fn($sq) => $sq->where('name', 'like', $term))
                  ->orWhereHas('tutor', fn($tq) => $tq->where('name', 'like', $term));
            });
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Delete inappropriate review.
     */
    public function destroyReview(Review $review): RedirectResponse
    {
        $tutorId = $review->tutor_id;
        $review->delete();

        // Recalculate tutor's rating
        $profile = TutorProfile::where('user_id', $tutorId)->first();
        if ($profile) {
            $profile->updateRatingCache();
        }

        return back()->with('success', 'Review deleted and tutor rating score recalculated.');
    }

    /**
     * 6. Analytics & Statistics Page.
     */
    public function stats(): View
    {
        // 1. Popular subjects breakdown
        $popularSubjects = Booking::select('subject', DB::raw('count(*) as total'))
            ->groupBy('subject')
            ->orderBy('total', 'desc')
            ->take(6)
            ->get();

        $maxSubjectCount = $popularSubjects->max('total') ?: 1;

        // 2. Top Tutors by Booking Count
        $topTutors = User::where('role', 'tutor')
            ->with('tutorProfile')
            ->withCount('tutorBookings')
            ->orderBy('tutor_bookings_count', 'desc')
            ->take(5)
            ->get();

        // 3. Monthly Revenue Summary (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = now()->subMonths($i);
            $rev = Booking::whereYear('created_at', $dt->year)
                ->whereMonth('created_at', $dt->month)
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum('total_amount');

            $monthlyRevenue[] = [
                'month' => $dt->format('M Y'),
                'revenue' => $rev,
            ];
        }

        // 4. User Growth Table by Month
        $userGrowth = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = now()->subMonths($i);
            $studentsCount = User::where('role', 'student')
                ->whereYear('created_at', $dt->year)
                ->whereMonth('created_at', $dt->month)
                ->count();

            $tutorsCount = User::where('role', 'tutor')
                ->whereYear('created_at', $dt->year)
                ->whereMonth('created_at', $dt->month)
                ->count();

            $userGrowth[] = [
                'month' => $dt->format('F Y'),
                'students' => $studentsCount,
                'tutors' => $tutorsCount,
                'total' => $studentsCount + $tutorsCount,
            ];
        }

        return view('admin.stats.index', compact('popularSubjects', 'maxSubjectCount', 'topTutors', 'monthlyRevenue', 'userGrowth'));
    }
}
