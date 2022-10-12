<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                =>(string)$this->id,
            'profile_image'     =>isset($this->profile_image) ? url('/images/profile-image',$this->profile_image) : '',
            'first_name'        =>(string)$this->first_name,
            'last_name'         =>(string)$this->last_name,
            'email'             =>(string)$this->email,
            'company_name'      =>(string)$this->client->company_name,
            'website'           =>(string)$this->client->website,
            'tagline'           =>(string)$this->client->tagline,
            'industry'          =>(string)$this->client->industry,
            'employee_no'       =>(string)$this->client->employee_no,
            'description'       =>(string)$this->client->description,
            'company_phone'     =>(string)$this->client->company_phone,
            'vat_id'            =>(string)$this->client->vat_id,
            'timezone'          =>(string)$this->timezone,
            'company_address'   =>(string)$this->client->company_address,
            'country'           =>(string)$this->country,
            'state'             =>(string)$this->state,
            'city'              =>(string)$this->city,
        ];
    }
}
