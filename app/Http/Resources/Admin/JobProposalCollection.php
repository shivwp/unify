<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class JobProposalCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
            return [
                'proposal_id'       =>(string)$data->send_proposal_id,
                'proposal_status'   =>($data->proposal_status == "pending") ? "submit" : "submit",
                'freelancer_id'     =>(string)$data->freelancer_id,
                'profile_image'     =>isset($data->profile_image) ? url('/images/profile-image',$data->profile_image) : '',
                'first_name'        =>isset($data->first_name) ? $data->first_name : '',
                'last_name'         =>isset($data->last_name) ? $data->last_name : '',
                'country'           =>isset($data->country) ? $data->country : '',
                'city'              =>isset($data->city) ? $data->city : '',
                'occcuption'        =>isset($data->occcuption) ? $data->occcuption : '',
                'amount'            =>(float)$data->amount,
                'earned'            =>(float)$data->total_earning,
                'cover_letter'      =>isset($data->cover_letter) ? $data->cover_letter : '',
                'skills'            =>$data->skills,
                'isShortlist'       =>$data->isShortlist($data->freelancer_id,$data->client_id,$data->project_id),
            ];
            
        });
    }
}
