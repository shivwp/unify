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

class ShortListed extends Authenticatable
{
    use Notifiable, HasApiTokens;

    public $table = 'save_shortlist';


    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected $fillable = [
      'freelancer_id',
      'client_id',
      'job_id',
    ];

    


}
