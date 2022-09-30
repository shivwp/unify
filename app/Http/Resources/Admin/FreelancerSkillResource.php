<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FreelancerSkillResource extends ResourceCollection
{
    public function toArray($request)
    {
        
        return $this->collection->map(function($data) {
            return [
                'id'            => $data->id,
                'skill_id'      => $data->skill_id,
                'skill_name'    => $data->skill_name,
            ];
        });
        
    }
}
