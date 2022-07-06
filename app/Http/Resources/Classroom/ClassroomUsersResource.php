<?php

namespace App\Http\Resources\Classroom;

use App\Http\Resources\StudentsResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class ClassroomUsersResource extends JsonResource
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
            'credit' => $this->credit,
            'deleted_at' => $this->deleted_at,
            'user' => new StudentsResource($this->user),
        ];
    }
}
