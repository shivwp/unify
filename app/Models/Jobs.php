<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Jobs extends Model
{
  

    public $table = 'jobs';

    protected $dates = [
        'created_at',
        'updated_at',
       
    ];

  

    // public function projects()
    // {
    //     return $this->belongsToMany(Project::class);
    // }
    public function user()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }
    public function project()
    {
        return $this->hasOne('App\Models\Project','id','project_id');
    }
    public function status()
    {
        return $this->hasOne('App\Models\ProjectStatus','id','status_id');
    }
    
}
