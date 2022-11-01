<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{

    public $table = 'agencies';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'plan_id',
        'title',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id', 'id');
    }

}
