<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InviteFreelacner extends Model
{
    use HasFactory;

    public $table = 'invite_freelancer';

    protected $fillable = [
        'client_id',
        'freelancer_id',
        'project_id',
        'status',
        'description'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
