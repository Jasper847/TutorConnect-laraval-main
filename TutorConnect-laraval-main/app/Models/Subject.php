<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
    ];

    public function tutors()
    {
        return $this->belongsToMany(TutorProfile::class, 'subject_tutor', 'subject_id', 'tutor_profile_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function studyMaterials()
    {
        return $this->hasMany(StudyMaterial::class);
    }
}
