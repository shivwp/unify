<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                =>$this->id,
            'first_name'        =>(string)$this->first_name,
            'last_name'         =>(string)$this->last_name,
            'email'             =>(string)$this->email,
            'phone'             =>(string)$this->phone,
            'email_verified_at' =>boolval($this->email_verified_at),
            'status'            =>(string)$this->status,
            'referal_code'      =>(string)$this->referal_code,
            'address'           =>(string)$this->address,
            'country'           =>(string)$this->country,
            'state'             =>(string)$this->state,
            'city'              =>(string)$this->city,
            'zip_code'          =>(string)$this->zip_code,
            'profile_image'     =>(string)$this->profile_image,
            'agree_terms'       =>(integer)$this->agree_terms,
            'send_email'        =>(integer)$this->send_email,
        ];
    }
}
