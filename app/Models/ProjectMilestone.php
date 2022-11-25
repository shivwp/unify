<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMilestone extends Model
{

    public $table = 'project_milestone';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'proposal_id',
        'project_id',
        'client_id',
        'freelancer_id',
        'description',
        'amount',
        'due_date',
        'status',
        'note',
        'type'
    ];
    
    public function projects()
    {
        return $this->hasMany(Project::class, 'id', 'project_id');
    }

    public function users()
    {
        return $this->hasMany(User::class,'id','freelancer_id');
    }

    public function client()
    {
        return $this->hasMany(User::class,'id','client_id');
    }
}
