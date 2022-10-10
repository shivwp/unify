<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FreelancerRating extends Model
{

    public $table = 'freelance_rating';

    protected $fillable = [
        'freelancer_id',
        'client_id',
        'project_id',
        'rating',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

   
}
