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
                'first_name' => (string)$data->first_name,
                'last_name'  => (string)$data->last_name,
                'email'      => (string)$data->email,
                'linkdin_url'=> (string)$data->linkdin_url,
                'title'      => (string)$data->title,
                'type'       => (string)$data->type,
                'description'=> (string)$data->description_freelancer,
            ];
        });
        
    }
}
