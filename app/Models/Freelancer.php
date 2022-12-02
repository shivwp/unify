<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Freelancer extends Model
{

    public $table = 'freelancer';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'visibility',
        'project_preference',
        'experience_level',
        'occcuption',
        'category',
        'description',
        'intro_video',
        'payment_base',
        'amount',
        'rating',
        'plan_id',
        'total_earning',
        'total_jobs',
        'total_hours',
        'pending_project',
        'country',
        'state',
        'city',
    ];

    public function freelancer_meta()
    {
        return $this->hasMany(FreelancerMeta::class, 'user_id', 'user_id');
    }
    public function freelancer_portfolio()
    {
        return $this->hasMany(FreelancerPortfolio::class, 'user_id', 'user_id');
    }
    public function freelancer_testimonial()
    {
        return $this->hasMany(FreelancerTestimonial::class, 'user_id', 'user_id');
    }
    public function freelancer_certificates()
    {
        return $this->hasMany(FreelancerCertificate::class, 'user_id', 'user_id');
    }
    public function freelancer_experiences()
    {
        return $this->hasMany(FreelancerExperience::class, 'user_id', 'user_id');
    }
    public function freelancer_skills()
    {
        return $this->hasMany(FreelancerSkill::class, 'user_id', 'user_id');
    }
    public function freelancer_education()
    {
        return $this->hasMany(FreelancerEducation::class, 'user_id', 'user_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function invite_freelancer()
    {
        return $this->hasMany(InviteFreelacner::class,'freelancer_id','user_id');
    }
    public function isInvite($id)
    {
        return $this->invite_freelancer()->where('freelancer_id', $id)->exists();
    }

    public function shortlist()
    {
        return $this->hasMany(ShortListed::class,'freelancer_id','user_id');
    }

    public function isShortlist($id)
    {
        return $this->shortlist()->where('freelancer_id', $id)->exists();
    }

    public function archived()
    {
        return $this->hasMany(SaveArchive::class,'freelancer_id','user_id');
    }

    public function isArchive($id)
    {
        return $this->archived()->where('freelancer_id', $id)->exists();
    }

    public function send_proposal()
    {
        return $this->hasMany(SendProposal::class, 'freelancer_id', 'user_id');
    }
}
