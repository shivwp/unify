<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriptionResource extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
            return [
                'id'                =>(string)$data->id,
                'title'             =>(string)$data->plans_title,
                'validity'          =>(string)$data->validity,
                'amount'            =>$data->amount,
                'description'       =>(string)$data->description,
                'services'          =>ServiceResource::collection($data->services)
            ];
        });
    }
}
