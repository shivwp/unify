<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FreelancerProposalCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
        return [
            
        ];
        });
    }
}
