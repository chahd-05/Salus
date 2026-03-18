<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'doctor_name',
        'specialty',
        'city',
        'yearsOfExperience',
        'consultationPrice',
        'availableDays'
    ];

    public function appointments() {
        return $this->hasMany(Appointment::class);
    }
}
