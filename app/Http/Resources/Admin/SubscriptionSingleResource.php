<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionSingleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                =>(string)$this->id,
            'title'             =>(string)$this->plans_title,
            'validity'          =>(string)$this->validity,
            'amount'            =>$this->amount,
            'description'       =>(string)$this->description,
            'services'          =>new ServiceCollectionResource($this->services)
        ];
    }
}
