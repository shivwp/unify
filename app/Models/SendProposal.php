<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendProposal extends Model
{

    public $table = 'send_proposals';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'job_id',
        'user_id',
        'status',
        'bid_amount',
        'platform_fee',
        'receive_amount',
        'project_duration',
        'cover_letter',
        'image',
    ];
    
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    
}
