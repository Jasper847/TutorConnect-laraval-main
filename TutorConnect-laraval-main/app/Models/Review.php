<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'student_id',
        'tutor_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /* -------------------------------------------------------------------------- */
    /*                                Relationships                               */
    /* -------------------------------------------------------------------------- */

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    /* -------------------------------------------------------------------------- */
    /*                                Booted Hook                                 */
    /* -------------------------------------------------------------------------- */

    protected static function booted()
    {
        static::saved(function ($review) {
            $tutorProfile = TutorProfile::where('user_id', $review->tutor_id)->first();
            if ($tutorProfile) {
                $tutorProfile->updateRatingCache();
            }
        });

        static::deleted(function ($review) {
            $tutorProfile = TutorProfile::where('user_id', $review->tutor_id)->first();
            if ($tutorProfile) {
                $tutorProfile->updateRatingCache();
            }
        });
    }
}
