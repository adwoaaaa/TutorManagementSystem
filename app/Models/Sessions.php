<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sessions extends Model
{
    use HasFactory;

    protected $fillable = [
        'repetition_status', 
        'repetition_period', 
        'session_status', 
        'session_request_form_id', 
        'student_id'
    ];


    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }


    public function sessionRequestForm(): BelongsTo
    {
        return $this->belongsTo(SessionRequestForm::class, 'session_request_form_id');
    }
}
