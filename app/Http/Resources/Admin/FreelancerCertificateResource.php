<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FreelancerCertificateResource extends ResourceCollection
{
    public function toArray($request)
    {
        
        return $this->collection->map(function($data) {
            return [
                'id'            => $data->id,
                'name'          => (string)$data->name,
                'issue_date'    => $data->issue_date,
                'expiry_date'   => $data->expiry_date,
                'certificate_id'=> $data->certificate_id,
            ];
        });
        
    }
}
