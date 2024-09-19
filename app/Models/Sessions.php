<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sessions extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_status', 
        'session_request_form_id', 
    ];


    public function sessionRequestForm(): BelongsTo
    {
        return $this->belongsTo(SessionRequestForm::class, 'session_request_form_id');
    }
}
