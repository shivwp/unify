<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class ProposalCollectionResource extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
            return [
                'id'                  =>(string)$data->auto_increment_id,
                'project_id'          =>(string)$data->id,
                'client_id'           =>(string)$data->client_id,
                'name'                =>(string)$data->name,
                'status'              =>(string)$data->status,
                'budget_type'         =>(string)$data->budget_type,
                'date'                =>Carbon::parse($data->created_at)->format('M d, Y'),
                'time'                =>$data->created_at->diffForHumans()
            ];
        });
    }
}
