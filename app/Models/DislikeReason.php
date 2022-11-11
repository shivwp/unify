<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DislikeReason extends Model
{
    use HasFactory;

    public $table = 'dislike_reasons';
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
    ];
}
