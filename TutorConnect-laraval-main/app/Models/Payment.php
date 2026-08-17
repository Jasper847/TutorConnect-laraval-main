<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'stripe_payment_intent_id',
        'amount',
        'currency',
        'status',
        'is_demo',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_demo' => 'boolean',
    ];

    /* -------------------------------------------------------------------------- */
    /*                                Relationships                               */
    /* -------------------------------------------------------------------------- */

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
