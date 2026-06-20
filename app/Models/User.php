<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'phone',
        'email',
        'user_type',
        'ref_id',
        'designation_id',
        'status',
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
        'created_at' => 'datetime',
    ];

    public function designation(){
        return $this->belongsTo(Designation::class,'designation_id');
    }


    protected static function booted()
    {
        static::creating(function ($user) {
            $user->user_id = 'LDZ';
        });

        static::created(function ($user) {
            $user->user_id = 'LDZ' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
            $user->save();
        });
    }


}

