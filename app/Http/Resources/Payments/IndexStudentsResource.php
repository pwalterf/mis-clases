<?php

namespace App\Http\Resources\Payments;

use App\Http\Resources\ClassroomUsers\ClassroomResource;
use App\Http\Resources\StudentsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexStudentsResource extends JsonResource
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
            'new_credit' => $this->studentPayment->new_credit,
            'user' => new StudentsResource($this->user),
            'classroom' => new ClassroomResource($this->classroom),
        ];
    }
}
