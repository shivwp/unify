<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedTalent extends Model
{
    use HasFactory;

    public $table = 'saved_talent';

    protected $fillable = [
        'client_id',
        'freelancer_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
