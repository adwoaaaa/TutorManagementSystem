<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionRequestForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject', 
        'course', 
        'level_of_education', 
        'session_period', 
        'venue', 
        'additional_information', 
        'duration', 
        'date', 
        'time', 
        'student'
    ];

    
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student');
    }
}
