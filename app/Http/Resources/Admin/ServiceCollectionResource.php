<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceCollectionResource extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function($data) {

        return [
            'service_name'            =>(string)$data->service_name,
            'description'             =>(string)$data->description
        ];
        });
    }
}
