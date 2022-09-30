<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FreelancerPortfolio extends Model
{

    public $table = 'freelancer_portfolio';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

   
}
