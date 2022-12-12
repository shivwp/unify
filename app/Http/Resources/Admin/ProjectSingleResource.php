<?php

namespace App\Http\Resources\Admin;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

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
            'min_price'         => isset($this->min_price) ? (integer)$this->min_price : 0,
            'price'             => isset($this->price) ? (integer)$this->price : 0,
            'project_duration'  => isset($this->project_duration) ? $this->project_duration : '',
            'scop'              => isset($this->scop) ? $this->scop : '',
            'status'            => isset($this->status) ? $this->status : '',
            'experience_level'  => isset($this->experience_level) ? $this->experience_level : '',
            'english_level'     => isset($this->english_level) ? $this->english_level : '',
            'categories'        => isset($this->categories->name) ? $this->categories->name : '',
            'category_id'       => isset($this->project_category) ? $this->project_category : '',
            'created_at'        => $this->created_at->diffForHumans(),
            'posted_date'        => Carbon::parse($this->created_at)->format('M d, Y'),
            'job_skills'        => SkillsResource::collection($this->skills),
            'proposal_list'     => $this->proposal_list,
            'client_data'       => new ClientResource($this->cdata),
            'is_private'        => boolval($this->is_private),
            'is_proposal_send'  => Auth::guard('api')->check() ? $this->isProposalSend(Auth::guard('api')->user()->id) : false,
            'is_saved'          => Auth::guard('api')->check() ? $this->isUserSaved(Auth::guard('api')->user()->id) : false,
            'service_fee'       => isset($this->service_fee) ? (integer)$this->service_fee : 0,
            'proposal_count'    => !empty($this->proposal_list) ? count($this->proposal_list) : 0,
            'invite_sent'       => $this->invite_sent,
            'unanswered_invite' => $this->unanswered_invite,
            'interview'         => (string)5,
            'hire_rate'         => (string)20,
            'open_jobs'         => (string)$this->open_job,
            'total_hire'        => (string)20,
            'total_Active'      => (string)2,
            'invite_id'         => $this->invite_id,
            'is_invited'    => boolval($this->invite_id),
            'client_recent_history'=> $this->recent_history,
        ];
    }
}
