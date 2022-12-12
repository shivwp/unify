<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FreelancerFilterCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
        return [
            'id'                =>(string)$data->user_id,
            'profile_image'     =>isset($data->profile_image) ? url('/images/profile-image',$data->profile_image) : '',
            'first_name'        =>isset($data->first_name) ? $data->first_name : '',
            'last_name'         =>isset($data->last_name) ? $data->last_name : '',
            'email'             =>isset($data->email) ? $data->email : '',
            'occuption'         =>isset($data->freelancer->occcuption) ? $data->freelancer->occcuption : '',
            'description'       =>isset($data->freelancer->description) ? $data->freelancer->description : '',
            'amount'            =>isset($data->amount) ? (integer)$data->amount : 0,
            'address'           =>isset($data->address) ? $data->address : '',
            'country'           =>isset($data->country) ? $data->country : '',
            'state'             =>isset($data->state) ? $data->state : '',
            'city'              =>isset($data->city) ? $data->city : '',
            'category_id'       =>isset($data->category) ?$data->category  : '',
            'category'          =>isset($data->projectCategoryName) ?$data->projectCategoryName  : '',
            'skills_count'      =>count($data->freelancerskills),
            'skills'            =>$data->freelancerskills,
            'isInvite'          =>$data->isInvite($data->user_id,$data->client_project->client_id,$data->client_project->id),    
            'isSaveTalent'      =>$data->isSaveTalent($data->user_id,$data->client_project->client_id), 
            'is_verified'       =>(string)$data->is_verified,   
            'success_rate'      =>"90%"
        ];
        });
    }
}
