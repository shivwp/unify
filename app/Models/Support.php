<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Support extends Model
{
    use SoftDeletes;

    public $table = 'support';
    protected $fillable = ['user_id', 'job_link', 'status', 'description', 'image'];
    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
      
    ];


    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
   
}
