<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'dni' => $this->dni,
            'birthdate' => $this->birthdate,
            'email' => $this->email,
            'created_at' => $this->created_at->format('d/m/Y'),
            'deleted_at' => $this->deleted_at,
            'roleNames' => $this->roles->pluck('name')->join(', ', ' y '),
        ];
    }
}
