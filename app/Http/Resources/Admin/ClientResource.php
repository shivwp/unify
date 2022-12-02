<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Stringable;

class ClientResource extends JsonResource
{
    public function toArray($request)
    {
        $last_view_time = Carbon::parse($this->last_activity);
        return [
            'id'                => (string)$this->id,
            'profile_image'     => isset($this->profile_image) ? url('/images/profile-image',$this->profile_image) : url('/images/profile-image/demo-user.png'),
            'first_name'        => (string)$this->first_name,
            'last_name'         => (string)$this->last_name,
            'email'             => (string)$this->email,
            'company_name'      => (string)$this->client->company_name,
            'website'           => (string)$this->client->website,
            'tagline'           => (string)$this->client->tagline,
            'industry'          => (string)$this->client->industry,
            'employee_no'       => (string)$this->client->employee_no,
            'description'       => (string)$this->client->description,
            'company_phone'     => (string)$this->client->company_phone,
            'vat_id'            => (string)$this->client->vat_id,
            'timezone'          => (string)$this->timezone,
            'local_time'        => !empty($this->timezone) ? Carbon::now()->timezone($this->timezone)->format('h:m A') : '',
            'company_address'   => (string)$this->client->company_address,
            'country'           => (string)$this->country,
            'state'             => (string)$this->state,
            'city'              => (string)$this->city,
            'zip_code'          => (string)$this->zip_code,
            'is_verified'       => (string)$this->is_verified,
            'payment_verified'  => ($this->payment_verified)?true:false,
            'rating'            => (string)3,
            'number_of_review'  => (string)1,
            'job_posted'        => (string)5, 
            'money_spent'       => (string)5000,
            'rate_paid_client'  => '20', 
            'member_since'      => $this->created_at->format('M d Y'),
            'last_activity'     => $last_view_time->diffForHumans(),
        ];
    }
}
