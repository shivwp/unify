<?php

namespace App\Http\Resources\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSingleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                =>(string)$this->id,
            'client_id'         =>(string)$this->client_id,
            'image'             =>isset($this->project_images) ? url('/images/jobs',$this->project_images) : '',
            'name'              =>isset($this->name) ? $this->name : '',
            'type'              =>isset($this->type) ? $this->type : '',
            'description'       =>isset($this->description) ? $this->description : '',
            'budget_type'       =>isset($this->budget_type) ? $this->budget_type : '',
            'min_price'         =>isset($this->min_price) ? $this->min_price : '',
            'price'             =>isset($this->price) ? $this->price : '',
            'project_duration'  =>isset($this->project_duration) ? $this->project_duration : '',
            'status'            =>isset($this->status) ? $this->status : '',
            'experience_level'  =>isset($this->experience_level) ? $this->experience_level : '',
            'categories'        =>isset($this->categories->name) ? $this->categories->name : '',
            'skills'            =>SkillsResource::collection($this->skills),
            'proposal_list'     =>$this->proposal_list,
            'client_data'       =>new ClientResource($this->cdata),
        ];
    }
}
