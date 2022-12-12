<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeclineReason extends Model
{

    public $table = 'decline_reasons';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'title',
        'type',
    ];

    public function proposals()
    {
        return $this->hasMany(SendProposal::class, 'id', 'proposal_id');
    }
}
