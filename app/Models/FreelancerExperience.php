<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerExperience extends Model
{
    use HasFactory;

    public $table = 'freelancer_experiences';

    protected $fillable = [
        'user_id',
        'subject',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
