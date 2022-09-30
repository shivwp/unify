<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerCertificate extends Model
{
    use HasFactory;

    public $table = 'freelancer_certificates';

    protected $fillable = [
        'user_id',
        'name',
        'issue_date',
        'expiry_date',
        'certificate_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
