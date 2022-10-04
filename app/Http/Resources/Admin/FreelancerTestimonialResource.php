<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FreelancerTestimonialResource extends ResourceCollection
{
    public function toArray($request)
    {
        
        return $this->collection->map(function($data) {
            return [
                'id'         => $data->id,
                'title'       => (string)$data->title,
                'type'       => (string)$data->type,
                'description'=> (string)$data->description,
            ];
        });
        
    }
}
