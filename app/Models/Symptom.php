<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'severity',
        'description',
        'note',
        'dateRecorded'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
