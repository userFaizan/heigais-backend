<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded=[];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'social_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function events(){
        return $this->hasMany(Event::class,'user_id');
    }
    public function notification_requests(){
        return $this->hasMany(NotificationRequest::class,'user_id');
    }
    public function transections(){
        return $this->hasMany(Transection::class,'user_id');
    }
    public function walets(){
        return $this->hasMany(Walet::class,'user_id');
    }
    public function language(){
        return $this->belongsTo(Language::class,'language_id');
    }
    public function unit(){
        return $this->belongsTo(Unit::class,'unit');
    }
    public function devices(){
        return $this->hasMany(UserDevice::class,'user_id');
    }


}
