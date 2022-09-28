<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectProjectCategory extends Model
{
    public $table = 'project_project_category';

    protected $fillable = [
        'project_id',
        'project_category_id',
    ];

    
}
