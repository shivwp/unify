<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
            return [
                'id'                =>(string)$data->id,
                'profile_image'     =>isset($data->profile_image) ? url('/images/profile-image',$data->profile_image) : '',
                'first_name'        =>(string)$data->first_name,
                'last_name'         =>(string)$data->last_name,
                'email'             =>(string)$data->email,
                'company_name'      =>(string)$data->client->company_name,
                'website'           =>(string)$data->client->website,
                'tagline'           =>(string)$data->client->tagline,
                'industry'          =>(string)$data->client->industry,
                'employee_no'       =>(string)$data->client->employee_no,
                'description'       =>(string)$data->client->description,
                'company_phone'     =>(string)$data->client->company_phone,
                'vat_id'            =>(string)$data->client->vat_id,
                'timezone'          =>(string)$data->timezone,
                'company_address'   =>(string)$data->client->company_address,
                'country'           =>(string)$data->country,
                'state'             =>(string)$data->state,
                'city'              =>(string)$data->city,
                'zip_code'          =>(string)$data->zip_code,
                'is_verified'       =>(string)$data->is_verified,
            ];
        });
    }
}
