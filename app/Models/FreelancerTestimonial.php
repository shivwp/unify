<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerTestimonial extends Model
{
    use HasFactory;

    public $table = 'freelancer_testimonial';

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
