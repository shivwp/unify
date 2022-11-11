<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContractsResource extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
            return [
                'id'                => (string)$data->id,
                'type'              => (string)($data->type??''),
                'project_id'        => (string)$data->project_id,
                'project_title'     => (string)($data->project_title??$data->projectDetails->name),
                'proposal_id'       => (string)($data->proposal_id??''),
                'start_time'        => (string)($data->start_time??''),
                'end_time'          => (string)($data->end_time??''),
                'client_id'         => (string)$data->client_id,
                'freelancer_id'     => (string)$data->freelancer_id,
                'amount'            => (string)$data->amount,
                'status'            => (string)$data->status,
                'project'           => new ProjectSingleResource($data->projectDetails),
                'proposal'          => $data->proposal?new ProposalResource($data->proposal):null,
                'client'            => new ClientResource($data->client),
                'freelancer'        => new FreelancerResource($data->freelancer),
                'created_at'        => (string)\Carbon\Carbon::parse($data->created_at)->format('Y-m-d'),
            ];
        });
    }
}
