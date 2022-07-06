<?php

namespace App\Http\Resources;

use App\Http\Resources\Payments\ClassroomUsersResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PaymentResource extends JsonResource
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
            'income' => $this->income,
            'payment_date' => $this->payment_date->format('Y-m-d'),
            'comment' => Str::limit($this->comment, 30),
            'students' => ClassroomUsersResource::collection($this->classroomUsers()->with('user', 'classroom')->get()),
        ];
    }
}
