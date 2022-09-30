<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Freelancer extends Model
{

    public $table = 'freelancer';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'occcuption',
        'description',
        'intro_video',
        'payment_base',
        'amount',
        'rating',
        'plan_id',
        'total_earning',
        'total_jobs',
        'total_hours',
        'pending_project',
        'country',
        'state',
        'city',
    ];

    public function freelancer_meta()
    {
        return $this->hasMany(FreelancerMeta::class, 'user_id', 'user_id');
    }
    public function freelancer_portfolio()
    {
        return $this->hasMany(FreelancerPortfolio::class, 'user_id', 'user_id');
    }


}
