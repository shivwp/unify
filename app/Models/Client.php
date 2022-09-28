<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    public $table = 'client';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'plan_id',
        'company_name',
        'industry',
        'tagline',
        'description',
        'employee_no',
        'vat_id',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id', 'id');
    }

}
