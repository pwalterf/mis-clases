<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomEditResource extends JsonResource
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
            'subscriptions' => SubscriptionResource::collection($this->subscriptions),
            'students' => StudentResource::collection($this->classroomUsers()->withTrashed()->with('user')->get()),
        ];
    }
}
