<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    public $table = 'projects';

    protected $dates = [
        'start_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'budget',
        'client_id',
        'status_id',
        'start_date',
        'end_date',
        'total_budget',
        'per_hour_budget',
        'created_at',
        'updated_at',
        'deleted_at',
        'description',
        'freelancer_type',
        'project_duration',
        'payment_base',
        'level',
        'english_level',
        'scop',
        'project_images',
    ];

    public function notes()
    {
        return $this->hasMany(Note::class, 'project_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'project_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'project_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function getStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function status()
    {
        return $this->belongsTo(ProjectStatus::class, 'status_id');
    }

    public function categories()
    {
        return $this->belongsToMany(ProjectCategory::class);
    }
     public function skills()
    {
        return $this->belongsToMany(ProjectSkill::class);
    }
     public function listingtypes()
    {
        return $this->belongsToMany(ProjectListingType::class);
    }
}
