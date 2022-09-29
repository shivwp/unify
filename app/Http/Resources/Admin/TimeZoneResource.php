<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class TimeZoneResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'country_code ' =>$this->id,
            'timezone '     =>(string)$this->first_name,
        ];
    }
}
