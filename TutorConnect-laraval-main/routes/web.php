<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student;
use App\Http\Controllers\Tutor;
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
    Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [Student\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [Student\ProfileController::class, 'update'])->name('profile.update');

    // Bookings & Checkout
    Route::get('/book/{tutor}', [Student\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/book/{tutor}', [Student\BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [Student\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [Student\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [Student\BookingController::class, 'cancel'])->name('bookings.cancel');

    // Stripe Sandbox Checkout
    Route::get('/payment/checkout/{booking}', [Student\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/process/{booking}', [Student\PaymentController::class, 'processSandbox'])->name('payment.process');
    Route::get('/payment/success/{booking}', [Student\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{booking}', [Student\PaymentController::class, 'cancel'])->name('payment.cancel');

    // Reviews
    Route::get('/bookings/{booking}/review', [Student\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/bookings/{booking}/review', [Student\ReviewController::class, 'store'])->name('reviews.store');

    // Messages & Study Materials
    Route::get('/messages', [Student\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{tutor}', [Student\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{tutor}', [Student\MessageController::class, 'send'])->name('messages.send');
    Route::get('/materials', [Student\StudyMaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/{material}/download', [Student\StudyMaterialController::class, 'download'])->name('materials.download');
});

// ==================== TUTOR PORTAL ====================
Route::prefix('tutor')->name('tutor.')->middleware(['auth', 'tutor'])->group(function () {
    Route::get('/dashboard', [Tutor\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [Tutor\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [Tutor\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/availability', [Tutor\AvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [Tutor\AvailabilityController::class, 'update'])->name('availability.update');

    // Bookings Management
    Route::get('/bookings', [Tutor\BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/confirm', [Tutor\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/complete', [Tutor\BookingController::class, 'complete'])->name('bookings.complete');
    Route::post('/bookings/{booking}/cancel', [Tutor\BookingController::class, 'cancel'])->name('bookings.cancel');

    // Reviews, Messages, Study Materials
    Route::get('/reviews', [Tutor\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/messages', [Tutor\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{student}', [Tutor\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{student}', [Tutor\MessageController::class, 'send'])->name('messages.send');
    Route::get('/materials', [Tutor\StudyMaterialController::class, 'index'])->name('materials.index');
    Route::post('/materials', [Tutor\StudyMaterialController::class, 'store'])->name('materials.store');
    Route::delete('/materials/{material}', [Tutor\StudyMaterialController::class, 'destroy'])->name('materials.destroy');
});

// ==================== ADMIN PORTAL ====================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Verifications
    Route::get('/verifications', [Admin\TutorVerificationController::class, 'index'])->name('verifications.index');
    Route::post('/verifications/{tutor}/verify', [Admin\TutorVerificationController::class, 'verify'])->name('verifications.verify');
    Route::post('/verifications/{tutor}/reject', [Admin\TutorVerificationController::class, 'reject'])->name('verifications.reject');

    // Bookings Audit
    Route::get('/bookings', [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [Admin\BookingController::class, 'cancel'])->name('bookings.cancel');

    // Subjects & Reviews
    Route::resource('subjects', Admin\SubjectController::class)->except(['create', 'show']);
    Route::get('/reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
});