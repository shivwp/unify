<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SkillsResource extends JsonResource
{
    public function toArray($request)
    {
        // return $this->collection->map(function($data) {
        return [
            'id'                =>(string)$this->id,
            'name'              =>isset($this->name) ? $this->name : '',
        ];
        // });
    }
}
