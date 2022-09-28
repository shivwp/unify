<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Support extends Model
{
    use SoftDeletes;

    public $table = 'support';

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
      
    ];

   
    public function project()
    {
        return $this->hasOne('App\Models\Project', 'id', 'project_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
   
}
