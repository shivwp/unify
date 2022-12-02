<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'service_name'            =>(string)$this->service_name,
            'description'             =>(string)$this->description
        ];
    }
}
