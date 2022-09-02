<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project_proposals extends Model
{
    use SoftDeletes;

    public $table = 'project_proposals';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function freelancer()
    {
        return $this->hasOne('App\User', 'id', 'freelancer_id');
    }
    public function project()
    {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }
}
