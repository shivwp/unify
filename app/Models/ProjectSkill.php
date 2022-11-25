<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSkill extends Model
{
    use SoftDeletes;

    public $table = 'project_skill';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'image',
        'short_description',
        'long_description',
        'banner_image',
    ];
    
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    
}
