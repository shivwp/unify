<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'profile_image'     =>isset($this->profile_image) ? url('/profile-image',$this->profile_image) : '',
            'first_name'        =>(string)$this->first_name,
            'last_name'         =>(string)$this->last_name,
            'email'             =>(string)$this->email,
            'company_name'      =>(string)$this->client->company_name,
            'website'           =>(string)$this->client->website,
            'description'       =>(string)$this->client->description,
            'company_email'     =>(string)$this->client->company_email,
            'company_phone'     =>(string)$this->client->company_phone,
            'vat_id'            =>(string)$this->client->vat_id,
            'timezone'          =>(string)$this->timezone,
            'company_address'   =>(string)$this->client->company_address,
        ];
    }
}
