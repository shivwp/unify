<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Business_size extends Model
{
  

    public $table = 'business_size';

    protected $dates = [
        'created_at',
        'updated_at',
       
    ];

  

    // public function projects()
    // {
    //     return $this->belongsToMany(Project::class);
    // }
    // public function user()
    // {
    //     return $this->hasOne('App\User','id','user_id');
    // }
    // public function project()
    // {
    //     return $this->hasOne('App\Project','id','project_id');
    // }
    // public function status()
    // {
    //     return $this->hasOne('App\ProjectStatus','id','status_id');
    // }
    
}
