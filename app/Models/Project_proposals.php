<?php

namespace App\Models;

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
        return $this->hasOne('App\Models\User', 'id', 'freelancer_id');
    }
    public function project()
    {
        return $this->hasOne('App\Models\Project', 'id', 'project_id');
    }
}
