<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'first_name' =>$this->first_name,
            'last_name' =>'sdfsdf',
            'email' =>'hjdsfkja@gmail.com',
        ];
    }
}
