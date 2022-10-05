<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FreelancerExperienceResource extends ResourceCollection
{
    public function toArray($request)
    {
        
        return $this->collection->map(function($data) {
            return [
                'id'                => $data->id,
                'company'           => $data->company,
                'city'              => $data->city,
                'country'           => $data->country,
                'start_date'        => $data->start_date,
                'end_date'          => $data->end_date,
                'currently_working' => $data->currently_working,
                'subject'           => (string)$data->subject,
                'description'       => $data->description,
            ];
        });
        
    }
}
