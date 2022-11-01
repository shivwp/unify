<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FreelancerCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
        return [
            'id'                =>(string)$data->id,
            'profile_image'     =>isset($data->profile_image) ? url('/images/profile-image',$data->profile_image) : '',
            'visibility'        =>isset($data->freelancer->visibility) ? $data->freelancer->visibility : '',
            'project_preference'=>isset($data->freelancer->project_preference) ? $data->freelancer->project_preference : '',
            'experience_level'  =>isset($data->freelancer->experience_level) ? $data->freelancer->experience_level : '',
            'first_name'        =>isset($data->first_name) ? $data->first_name : '',
            'last_name'         =>isset($data->last_name) ? $data->last_name : '',
            'email'             =>isset($data->email) ? $data->email : '',
            'occuption'         =>isset($data->freelancer->occcuption) ? $data->freelancer->occcuption : '',
            'description'       =>isset($data->freelancer->description) ? $data->freelancer->description : '',
            'rating'            =>isset($data->freelancer->rating) ? $data->freelancer->rating : '',
            'total_earning'     =>isset($data->freelancer->total_earning) ? (float)$data->freelancer->total_earning : 0.00,
            'total_jobs'        =>isset($data->freelancer->total_jobs) ? (integer)$data->freelancer->total_jobs : 0,
            'total_hours'       =>isset($data->freelancer->total_hours) ? (integer)$data->freelancer->total_hours : 0,
            'pending_project'   =>isset($data->freelancer->pending_project) ? (integer)$data->freelancer->pending_project : 0,
            'timezone'          =>isset($data->timezone) ? $data->timezone : '',
            'address'           =>isset($data->address) ? $data->address : '',
            'phone'             =>isset($data->phone) ? $data->phone : '',
            'country'           =>isset($data->country) ? $data->country : '',
            'state'             =>isset($data->state) ? $data->state : '',
            'city'              =>isset($data->city) ? $data->city : '',
            'online_status'     =>isset($data->online_status) ? $data->online_status : '',
            'is_verified'       =>boolval($data->is_verified),
        ];
        });
    }
}
