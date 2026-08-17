<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'headline',
        'bio',
        'subjects',
        'hourly_rate',
        'experience_years',
        'education',
        'location',
        'is_verified',
        'is_available',
        'avg_rating',
        'reviews_count',
    ];

    protected $casts = [
        'subjects' => 'array',
        'hourly_rate' => 'decimal:2',
        'experience_years' => 'integer',
        'is_verified' => 'boolean',
        'is_available' => 'boolean',
        'avg_rating' => 'decimal:2',
        'reviews_count' => 'integer',
    ];

    /* -------------------------------------------------------------------------- */
    /*                                Relationships                               */
    /* -------------------------------------------------------------------------- */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function availabilities()
    {
        return $this->hasMany(AvailabilitySlot::class, 'tutor_id', 'user_id');
    }

    public function availabilitySlots()
    {
        return $this->hasMany(AvailabilitySlot::class, 'tutor_id', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'tutor_id', 'user_id');
    }

    /* -------------------------------------------------------------------------- */
    /*                               Rating Cache                                 */
    /* -------------------------------------------------------------------------- */

    public function updateRatingCache(): void
    {
        $avg = $this->reviews()->avg('rating') ?? 0.0;
        $count = $this->reviews()->count();

        $this->update([
            'avg_rating' => round($avg, 2),
            'reviews_count' => $count,
        ]);
    }
}
