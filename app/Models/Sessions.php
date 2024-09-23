<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Sessions extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'session_request_form_id', 
        'session_status',
    ];

    protected $keyType = 'string'; 
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid(); // Generate a UUID for the 'id'
            }
        });
    }


    public function sessionRequestForm(): BelongsTo
    {
        return $this->belongsTo(SessionRequestForm::class, 'session_request_form_id');
    }
}
