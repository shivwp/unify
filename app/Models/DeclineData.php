<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeclineData extends Model
{

    public $table = 'decline_data';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'data_id',
        'client_id',
        'freelancer_id',
        'project_id',
        'reason',
        'description',
        'type',
    ];

    public function proposals()
    {
        return $this->hasMany(SendProposal::class, 'id', 'proposal_id');
    }
}
