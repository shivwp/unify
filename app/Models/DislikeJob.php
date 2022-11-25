<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DislikeJob extends Model
{
    use HasFactory;

    public $table = 'dislike_jobs';
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'job_id',
        'user_id',
        'reason_id',
        'status',
    ];
}
