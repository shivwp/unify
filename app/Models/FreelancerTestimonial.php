<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerTestimonial extends Model
{
    use HasFactory;

    public $table = 'freelancer_testimonial';

    protected $fillable = [
        'user_id',
        'title',
        'linkdin_url',
        'first_name',
        'last_name',
        'email',
        'type',
        'status',
        'description_freelancer',
        'description_client',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
