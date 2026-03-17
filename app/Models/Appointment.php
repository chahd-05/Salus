<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'appointmentDate',
        'status',
        'note'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }
}
