<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    // use Sluggable;

    public $table = 'projects';

    protected $dates = [
        'start_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'slug',
        'description',
        'start_date',
        'end_date',
        'min_price',
        'price',
        'client_id',
        'status',
        'project_duration',
        'project_category',
        'budget_type',
        'experience_level',
        'scop',
        'project_images',
        'created_at',
        'updated_at',
        'deleted_at',
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
        return $this->hasOne('App\Models\User', 'id', 'client_id');
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
        return $this->belongsTo(ProjectCategory::class,'project_category');
    }
     public function skills()
    {
        return $this->belongsToMany(ProjectSkill::class);
    }
     public function listingtypes()
    {
        return $this->belongsToMany(ProjectListingType::class);
    }

    protected $appends = ["service_fee"];

    public function saved_projects()
    {
        return $this->hasMany(SavedProject::class);
    }

    public function getServiceFeeAttribute()
    {   
        $service_fee = SiteSetting::where('name', 'servicefee')->first();
        return empty($service_fee)?'':$service_fee->value;
    }

    public function isUserSaved($id)
    {
        return $this->saved_projects()->where('user_id', $id)->exists();
    }

    public function is_proposal_send()
    {
        return $this->hasMany(SendProposal::class, 'project_id');
    }

    public function isProposalSend($id)
    {
        return $this->is_proposal_send()->where('freelancer_id', $id)->exists();
    }
    
    // public function sluggable(): array

    // {

    //     return [

    //         'slug' => [

    //             'source' => 'name'

    //         ]

    //     ];

    // }
 }
