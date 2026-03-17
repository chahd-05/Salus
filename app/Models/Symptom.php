<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable = [
        'name',
        'severity',
        'description',
        'note'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
