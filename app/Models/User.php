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
    use Notifiable, HasApiTokens;

    public $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'social_id',
        'api_token',
        'device_id',
        'device_token',
        'customer_id',
        'plan_id',
        'email_verified_at',
        'otp',
        'otp_created_at',
        'status',
        'close_status',
        'online_status',
        'is_verified',
        'password',
        'referal_code',
        'address',
        'country',
        'state',
        'city',
        'zip_code',
        'timezone',
        'profile_image',
        'remember_token',
        'avg_rating',
        'agree_terms',
        'send_email',
        'payment_verified',
        'is_profile_complete',
        'last_activity',
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
        return $this->hasOne(Freelancer::class,'user_id', 'id');
    }
    public function invite_freelancer()
    {
        return $this->hasMany(InviteFreelacner::class,'freelancer_id','id');
    }
    public function isInvite($id)
    {
        return $this->invite_freelancer()->where('freelancer_id', $id)->exists();
    }

    public function save_talent()
    {
        return $this->hasMany(SavedTalent::class, 'freelancer_id', 'id');
    }

    public function isSaveTalent($id)
    {
        return $this->save_talent()->where('freelancer_id', $id)->exists();
    }
}
