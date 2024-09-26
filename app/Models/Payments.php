<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount', 
        'method', 
        'description', 
        'session_id', 
        'student', 
        'status'
    ];


    public function session(): BelongsTo
    {
        return $this->belongsTo(Sessions::class, 'session_id');
    }

    
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student');
    }
}
