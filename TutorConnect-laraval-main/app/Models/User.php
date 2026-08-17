<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'profile_photo',
        'city',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /* -------------------------------------------------------------------------- */
    /*                                Role Helpers                                */
    /* -------------------------------------------------------------------------- */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTutor(): bool
    {
        return $this->role === 'tutor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /* -------------------------------------------------------------------------- */
    /*                                Relationships                               */
    /* -------------------------------------------------------------------------- */

    public function tutorProfile()
    {
        return $this->hasOne(TutorProfile::class, 'user_id');
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }

    public function availabilitySlots()
    {
        return $this->hasMany(AvailabilitySlot::class, 'tutor_id');
    }

    public function studentBookings()
    {
        return $this->hasMany(Booking::class, 'student_id');
    }

    public function tutorBookings()
    {
        return $this->hasMany(Booking::class, 'tutor_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'student_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'tutor_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function studyMaterials()
    {
        return $this->hasMany(StudyMaterial::class, 'tutor_id');
    }

    /* -------------------------------------------------------------------------- */
    /*                                  Accessors                                 */
    /* -------------------------------------------------------------------------- */

    public function getAvatarUrlAttribute(): string
    {
        if ($this->profile_photo && file_exists(public_path('storage/' . $this->profile_photo))) {
            return asset('storage/' . $this->profile_photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1e40af&color=ffffff&size=200&bold=true';
    }
}
