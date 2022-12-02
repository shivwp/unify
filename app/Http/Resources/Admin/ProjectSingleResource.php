<?php

namespace App\Http\Resources\Admin;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProjectSingleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => (string)$this->id,
            'client_id'         => (string)$this->client_id,
            'image'             => isset($this->project_images) ? url('/images/jobs',$this->project_images) : '',
            'image_name'        => isset($this->project_images) ? (string)($this->project_images) : '',
            'name'              => isset($this->name) ? $this->name : '',
            'type'              => isset($this->type) ? $this->type : '',
            'description'       => isset($this->description) ? $this->description : '',
            'budget_type'       => isset($this->budget_type) ? $this->budget_type : '',
            'min_price'         => isset($this->min_price) ? $this->min_price : '',
            'price'             => isset($this->price) ? $this->price : '',
            'project_duration'  => isset($this->project_duration) ? $this->project_duration : '',
            'scop'              => isset($this->scop) ? $this->scop : '',
            'status'            => isset($this->status) ? $this->status : '',
            'experience_level'  => isset($this->experience_level) ? $this->experience_level : '',
            'english_level'     => isset($this->english_level) ? $this->english_level : '',
            'categories'        => isset($this->categories->name) ? $this->categories->name : '',
            'created_at'        => $this->created_at->diffForHumans(),
            'skills'            => SkillsResource::collection($this->skills),
            'proposal_list'     => $this->proposal_list,
            'client_data'       => new ClientResource($this->cdata),
            'client_recent_history'=> $this->recent_history,
            'is_proposal_send'  => Auth::guard('api')->check() ? $this->isProposalSend(Auth::guard('api')->user()->id) : false,
            'is_saved'          => Auth::guard('api')->check() ? $this->isUserSaved(Auth::guard('api')->user()->id) : false,
            'service_fee'       => isset($this->service_fee) ? $this->service_fee : '',
            'proposal_count'    => count($this->proposal_list),
            'invite_sent'       => $this->invite_sent,
            'unanswered_invite' => $this->unanswered_invite,
            'interview'         => (string)5,
            
        ];
    }
}
