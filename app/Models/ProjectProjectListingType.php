<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectProjectListingType extends Model
{

    public $table = 'project_project_listing_type';

    protected $fillable = [
        'project_id',
        'project_listing_type_id',
        'updated_at',
        'deleted_at',
    ];

    
}
