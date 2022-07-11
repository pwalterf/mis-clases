<?php

namespace App\Http\Resources\Classroom;

use App\Http\Resources\SubscriptionsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
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
            'deleted_at' => $this->deleted_at,
            'subscriptions' => SubscriptionsResource::collection($this->subscriptions),
            'classroomUsers' => ClassroomUsersResource::collection($this->classroomUsers()->withTrashed()->with('user')->get()),
        ];
    }
}
