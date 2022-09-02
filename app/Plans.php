<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plans extends Model
{
    use SoftDeletes;

    public $table = 'plans';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

  

    // public function projects()
    // {
    //     return $this->belongsToMany(Project::class);
    // }
    // public function parentcategory()
    // {
    //     return $this->hasOne('App\ProjectCategory','id','parent_id');
    // }
    public function services()
    {
        return $this->belongsToMany(Services::class);
    }
    
}
