<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Tutor;
use App\Http\Controllers\Tutor\TutorProfileController;
use App\Http\Controllers\TutorDirectoryController;
use Illuminate\Support\Facades\Route;

// ==================== PUBLIC ROUTES ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/tutors', [TutorDirectoryController::class, 'index'])->name('tutors.index');
Route::get('/tutors/{id}', [TutorDirectoryController::class, 'show'])->name('tutors.show');

// ==================== AUTHENTICATION ROUTES ====================
require __DIR__.'/auth.php';

// ==================== STUDENT PORTAL ====================
Route::prefix('student')->name('student.')->middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [StudentController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');

    // Search & Browse Tutors
    Route::get('/tutors', [StudentController::class, 'searchTutors'])->name('tutors.index');
    Route::get('/tutors/{id}', [StudentController::class, 'tutorDetail'])->name('tutors.show');
    Route::get('/tutors/{tutor}/slots', [StudentController::class, 'getTutorSlots'])->name('tutors.slots');

    // Bookings & Checkout
    Route::get('/book/{tutor}', [StudentController::class, 'bookTutor'])->name('bookings.create');
    Route::post('/book/{tutor}', [StudentController::class, 'storeBooking'])->name('bookings.store');
    Route::get('/bookings', [StudentController::class, 'myBookings'])->name('bookings.index');
    Route::get('/bookings/{booking}', [Student\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [Student\BookingController::class, 'cancel'])->name('bookings.cancel');

    // Stripe Sandbox Checkout
    Route::get('/payment/checkout/{booking}', [Student\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/process/{booking}', [Student\PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/success/{booking}', [Student\PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/cancel/{booking}', [Student\PaymentController::class, 'paymentCancel'])->name('payment.cancel');

    // Reviews
    Route::get('/bookings/{booking}/review', [StudentController::class, 'leaveReview'])->name('reviews.create');
    Route::post('/bookings/{booking}/review', [StudentController::class, 'storeReview'])->name('reviews.store');

    // Messages & Study Materials
    Route::get('/messages', [Student\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{tutor}', [Student\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{tutor}', [Student\MessageController::class, 'send'])->name('messages.send');
    Route::get('/materials', [Student\StudyMaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/{material}/download', [Student\StudyMaterialController::class, 'download'])->name('materials.download');
});

// ==================== TUTOR PORTAL ====================
Route::prefix('tutor')->name('tutor.')->middleware(['auth', 'tutor'])->group(function () {
    Route::get('/dashboard', [TutorProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [TutorProfileController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [TutorProfileController::class, 'updateProfile'])->name('profile.update');
    
    // Availability
    Route::get('/availability', [TutorProfileController::class, 'setAvailability'])->name('availability.index');
    Route::post('/availability', [TutorProfileController::class, 'updateAvailability'])->name('availability.update');

    // Bookings Management
    Route::get('/bookings', [TutorProfileController::class, 'myBookings'])->name('bookings.index');
    Route::post('/bookings/{booking}/confirm', [TutorProfileController::class, 'confirmBooking'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/complete', [TutorProfileController::class, 'completeBooking'])->name('bookings.complete');
    Route::post('/bookings/{booking}/cancel', [TutorProfileController::class, 'cancelBooking'])->name('bookings.cancel');

    // Reviews
    Route::get('/reviews', [TutorProfileController::class, 'myReviews'])->name('reviews.index');

    // Messages & Materials
    Route::get('/messages', [Tutor\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{student}', [Tutor\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{student}', [Tutor\MessageController::class, 'send'])->name('messages.send');
    Route::get('/materials', [Tutor\StudyMaterialController::class, 'index'])->name('materials.index');
    Route::post('/materials', [Tutor\StudyMaterialController::class, 'store'])->name('materials.store');
    Route::delete('/materials/{material}', [Tutor\StudyMaterialController::class, 'destroy'])->name('materials.destroy');
});

// ==================== ADMIN PORTAL ====================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [Admin\AdminController::class, 'users'])->name('users.index');
    Route::post('/users/{user}/toggle-status', [Admin\AdminController::class, 'toggleUserStatus'])->name('users.toggle');
    Route::delete('/users/{user}', [Admin\AdminController::class, 'destroyUser'])->name('users.destroy');

    // Tutors Management & Verifications
    Route::get('/tutors', [Admin\AdminController::class, 'tutors'])->name('tutors.index');
    Route::get('/verifications', [Admin\AdminController::class, 'tutors'])->name('verifications.index');
    Route::post('/tutors/{tutor}/verify', [Admin\AdminController::class, 'verifyTutor'])->name('tutors.verify');
    Route::post('/tutors/{tutor}/unverify', [Admin\AdminController::class, 'unverifyTutor'])->name('tutors.unverify');
    Route::post('/verifications/{tutor}/verify', [Admin\AdminController::class, 'verifyTutor'])->name('verifications.verify');
    Route::post('/verifications/{tutor}/reject', [Admin\AdminController::class, 'unverifyTutor'])->name('verifications.reject');

    // Bookings Audit
    Route::get('/bookings', [Admin\AdminController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{booking}', [Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [Admin\AdminController::class, 'cancelBooking'])->name('bookings.cancel');

    // Reviews Moderation
    Route::get('/reviews', [Admin\AdminController::class, 'reviews'])->name('reviews.index');
    Route::delete('/reviews/{review}', [Admin\AdminController::class, 'destroyReview'])->name('reviews.destroy');

    // Statistics & Analytics
    Route::get('/stats', [Admin\AdminController::class, 'stats'])->name('stats.index');
    
    // Subject catalog
    Route::resource('subjects', Admin\SubjectController::class)->except(['create', 'show']);
});