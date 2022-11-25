<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class FreelancerTestimonialResource extends ResourceCollection
{
    public function toArray($request)
    {
        
        return $this->collection->map(function($data) {

            $testimonialData = [
                'id'            => $data->id,
                'first_name'    => (string)$data->first_name,
                'last_name'     => (string)$data->last_name,
                'email'         => (string)$data->email,
                'title'         => (string)$data->title,
                'type'          => (string)$data->type,
                'description'   => (string)$data->description_client,
                'status'        => isset($data->status) && ( $data->status == 0) ? 'pending':'approve',
                'request_sent'  => date_format($data->created_at,'M d Y'),
            ];
            $pendingData = [
                'id'            => $data->id,
                'message'       => "Your testimonial request is awaiting ".$data->first_name." 's response",
                'request_sent'  => date_format($data->created_at,'M d Y'),
                'status'        => isset($data->status) && ( $data->status == 0) ? 'pending':'approve',
            ];
            if($data->status == 1){
                return $testimonialData;
            }else{
                return $pendingData;
            }
        });
        
    }
}
