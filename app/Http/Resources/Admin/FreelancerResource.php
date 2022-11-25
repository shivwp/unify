<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class FreelancerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                =>(string)$this->id,
            'profile_image'     =>isset($this->profile_image) ? url('/images/profile-image',$this->profile_image) : '',
            'visibility'        =>(string)$this->freelancer->visibility,
            'project_preference'=>(string)$this->freelancer->project_preference,
            'experience_level'  =>(string)$this->freelancer->experience_level,
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
            'amount'            =>(string)$this->freelancer->amount,
            'timezone'          =>(string)$this->timezone,
            'address'           =>(string)$this->address,
            'phone'             =>(string)$this->phone,
            'country'           =>(string)$this->country,
            'state'             =>(string)$this->state,
            'city'              =>(string)$this->city,
            'zip_code'          =>(string)$this->zip_code,
            'is_verified'       =>(string)$this->is_verified,
            'hours_per_week'    =>(string)$this->hours_per_week,
        ];
    }
}
