<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ClientRating extends Model
{

    public $table = 'client_rating';

    protected $fillable = [
        'freelancer_id',
        'client_id',
        'project_id',
        'rating',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

   
}
