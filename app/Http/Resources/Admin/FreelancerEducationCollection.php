<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FreelancerEducationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
            return [
                'id'            => $data->id,
                'school'        => (string)$data->school,
                'start_year'    => (string)$data->start_date,
                'end_year'      => (string)$data->end_date,
                'level'         => (string)$data->level,
                'degree'        => (string)$data->degree,
                'area_study'    => (string)$data->area_study,
                'description'   => (string)$data->description,
            ];
        });
    }
}
