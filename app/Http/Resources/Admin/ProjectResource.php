<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectResource extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
            return [
                'id'                => (string)$data->id,
                'image'             => isset($data->project_images) ? url('/images/jobs',$data->project_images) : '',
                'name'              => isset($data->name) ? $data->name : '',
                'type'              => isset($data->type) ? $data->type : '',
                'is_proposal_send'  => isset($this->user_id)?$data->isProposalSend($this->user_id):false,
                'description'       => isset($data->description) ? $data->description : '',
                'budget_type'       => isset($data->budget_type) ? $data->budget_type : '',
                'min_price'         => (integer)$data->min_price,
                'price'             => (integer)$data->price,
                'project_duration'  => isset($data->project_duration) ? $data->project_duration : '',
                'status'            => isset($data->status) ? $data->status : '',
                'experience_level'  => isset($data->experience_level) ? $data->experience_level : '',
                'english_level'     => isset($data->english_level) ? $data->english_level : '',
                'scop'              => isset($data->scop) ? $data->scop : '',
                'categories'        => isset($data->categories->name) ? $data->categories->name : '',
                'skills'            => SkillsResource::collection($data->skills),
                'is_saved'          => isset($this->user_id)?$data->isUserSaved($this->user_id):false,
                'created_at'        => $data->created_at->diffForHumans(),
            ];
        });
    }
}
