<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedProject extends Model
{

    public $table = 'saved_projects';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'project_id',
    ];
    
    public function projects()
    {
        return $this->belongsToMany(Project::class,'saved_projects','id','project_id');
    }

    
}
