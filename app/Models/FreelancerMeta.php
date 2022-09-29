<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreelancerMeta extends Model
{
    use SoftDeletes;

    public $table = 'freelancer_meta';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'meta_key',
        'meta_value',
    ];


}
