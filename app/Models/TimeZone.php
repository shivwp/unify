<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TimeZone extends Model
{

    public $table = 'timezone';

    protected $fillable = [
        'country_code ',
        'timezone ',
        'gmt_offset',
        'dst_offset',
        'raw_offset',
    ];

   
}
