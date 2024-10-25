<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        //'student',
       // 'name',          // Name for public submissions
        'email',         // Email for public submissions
       // 'phoneNumber',   // Phone number for public submissions
        'message',
    ];

 /*   public function student(): Belongsto
    {
       return $this->belongsTo(User::class, 'student', 'id');
    }*/
}
