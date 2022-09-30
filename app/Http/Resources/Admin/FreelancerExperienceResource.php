<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FreelancerExperienceResource extends ResourceCollection
{
    public function toArray($request)
    {
        
        return $this->collection->map(function($data) {
            return [
                'id'             => $data->id,
                'subject'        => (string)$data->subject,
                'description'    => $data->description,
            ];
        });
        
    }
}
