<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Contracts\Providers\JWT;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'lastName',
        'otherNames',
        'email',
        'phoneNumber',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public $incrementing = false;
    protected $keyType = 'string';

   /**
     * Get the identifier that will be stored in the JWT payload.
     *
     * @return mixed
     */
   public function getJWTIdentifier()
   {
       return $this->getKey();
   }

   /**
    * Get the custom claims array to be added to the JWT payload.
    *
    * @return array
    */
   public function getJWTCustomClaims()
   {
       return [];
   }

   // Relationships with other models

   public function sessionRequests()
   {
       return $this->hasMany(SessionRequestForm::class, 'student');
   }

/*
   public function sessions()
   {
       return $this->hasMany(Session::class, 'student_id');
   }
   */


   public function payments()
    {
        return $this->hasMany(Payments::class, 'student');
    }

}
