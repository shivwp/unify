<?php

namespace App\Http\Resources\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalSingleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => (string)$this->id,
            'project_id'            => (string)$this->job_id,
            'user_id'               => (string)$this->user_id,
            'bid_amount'            => (string)$this->bid_amount,
            'receive_amount'        => (string)$this->receive_amount,
            'project_duration'      => (string)$this->project_duration,
            'cover_letter'          => (string)$this->cover_letter,
            'status'                => (string)$this->status,
            'platform_fee'          => (string)$this->platform_fee,
            'image'                 => (string)$this->image,
        ];
    }
}
