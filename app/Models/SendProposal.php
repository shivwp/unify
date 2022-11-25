<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendProposal extends Model
{

    public $table = 'send_proposals';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'project_id',
        'client_id',
        'freelancer_id',
        'budget_type',
        'amount',
        'weekly_limit',
        'title',
        'date',
        'cover_letter', 
        'image',
        'status'
    ];
    
    public function projects()
    {
        // return $this->belongsToMany(Project::class);
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
