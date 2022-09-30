<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerSkill extends Model
{
    use HasFactory;

    public $table = 'freelancer_skills';

    protected $fillable = [
        'user_id',
        'skill_id',
        'skill_name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
