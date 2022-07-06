<?php

namespace App\Http\Resources\Payments;

use App\Http\Resources\ClassroomUsers\ClassroomResource;
use App\Http\Resources\StudentsResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'classroom' => new ClassroomResource($this->classroom),
            'credit' => $this->credit,
            'deleted_at' => $this->deleted_at,
            'id' => $this->id,
            'new_credit' => $this->studentPayment?->new_credit . '',
            'price_hr' => $this->classroom->subscriptions()->first()->price_hr,
            'user' => new StudentsResource($this->user),
        ];
    }
}
