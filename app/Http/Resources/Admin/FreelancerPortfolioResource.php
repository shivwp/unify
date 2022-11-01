<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FreelancerPortfolioResource extends ResourceCollection
{
    public function toArray($request)
    {
        
        return $this->collection->map(function($data) {
            return [
                'id'         => $data->id,
                'name'       => (string)$data->title,
                'description'=> (string)$data->description,
                'image'      => isset($data->image) ? url('/images/freelancer-portfolio',$data->image) : '',
            ];
        });
        
    }
}
