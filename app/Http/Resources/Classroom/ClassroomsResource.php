<?php

namespace App\Http\Resources\Classroom;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at->format('d/m/Y'),
            'price_hr' => $this->price_hr,
        ];
    }
}
