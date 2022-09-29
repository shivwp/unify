<?php

namespace App\Models;

use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasApiTokens;

    public $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'email_verified_at',
        'otp',
        'otp_created_at',
        'status',
        'password',
        'referal_code',
        'address',
        'country',
        'state',
        'city',
        'zip_code',
        'profile_image',
        'remember_token',
        'agree_terms',
        'send_email',
    ];

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? $value : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function skills()
    {
        return $this->belongsToMany(ProjectSkill::class);
    }
    public function client()
    {
        return $this->hasOne(Client::class,'user_id');
    }
    public function freelancer()
    {
        return $this->hasOne(Freelancer::class,'user_id');
    }
}
