<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerEducation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school',
        'date',
        'level',
        'degree',
        'area_study',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
