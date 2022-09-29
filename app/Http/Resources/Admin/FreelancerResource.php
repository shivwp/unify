<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class FreelancerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'profile_image'     =>isset($this->profile_image) ? url('/profile-image',$this->profile_image) : '',
            'first_name'        =>(string)$this->first_name,
            'last_name'         =>(string)$this->last_name,
            'email'             =>(string)$this->email,
            'occuption'         =>(string)$this->freelancer->occcuption,
            'description'       =>(string)$this->freelancer->description,
            'rating'            =>(string)$this->freelancer->rating,
            'total_earning'     =>(float)$this->freelancer->total_earning,
            'total_jobs'        =>(integer)$this->freelancer->total_jobs,
            'total_hours'       =>(integer)$this->freelancer->total_hours,
            'pending_project'   =>(integer)$this->freelancer->pending_project,
            'country'           =>(string)$this->country,
            'state'             =>(string)$this->state,
            'city'              =>(string)$this->city,
        ];
    }
}