<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contracts extends Model
{

    public $table = 'contracts';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'type',
        'project_id',
        'project_title',
        'proposal_id',
        'start_time',
        'end_time',
        'client_id',
        'freelancer_id',
        'amount',
        'status'
    ];
  

    // public function projects()
    // {
    //     return $this->belongsToMany(Project::class);
    // }
    public function client()
    {
        return $this->hasOne('App\Models\User','id','client_id');
    }

    public function freelancer()
    {
        return $this->hasOne('App\Models\User','id','freelancer_id');
    }

    public function projectDetails()
    {
        return $this->hasOne('App\Models\Project','id','project_id');
    }

    public function proposal()
    {
        return $this->hasOne('App\Models\SendProposal','id','proposal_id');
    }

    // public function status()
    // {
    //     return $this->hasOne('App\Models\ProjectStatus','id','status_id');
    // }
    
}
