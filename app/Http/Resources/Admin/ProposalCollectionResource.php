<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProposalCollectionResource extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
            return [
                'project_id'          =>(string)$data->id,
                'proposal_id'         =>(string)$data->send_proposal_id,
                'client_id'           =>(string)$data->client_id,
                'name'                =>(string)$data->name,
                'proposal_description'=>(string)$data->cover_letter,
                'project_description' =>(string)$data->description,
                'status'              =>(string)$data->status,
                'time'                =>$data->created_at->diffForHumans()
            ];
        });
    }
}
