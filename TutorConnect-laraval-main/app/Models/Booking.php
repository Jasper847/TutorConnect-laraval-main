<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'student_id',
        'tutor_id',
        'subject',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'mode',
        'notes',
        'total_amount',
        'cancellation_reason',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /* -------------------------------------------------------------------------- */
    /*                                Relationships                               */
    /* -------------------------------------------------------------------------- */

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'booking_id');
    }

    /* -------------------------------------------------------------------------- */
    /*                               Status Helpers                               */
    /* -------------------------------------------------------------------------- */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function canBeReviewedBy(int $userId): bool
    {
        return $this->status === 'completed' && $this->student_id === $userId && !$this->review()->exists();
    }
}