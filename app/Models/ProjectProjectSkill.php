<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectProjectSkill extends Model
{

    public $table = 'project_project_skill';

    protected $fillable = [
        'project_id',
        'project_skill_id',
        'updated_at',
        'deleted_at',
    ];

    
}
