<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecializeProfile extends Model
{

    public $table = 'specialize_profile';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'title',
        'description',
    ];


}
