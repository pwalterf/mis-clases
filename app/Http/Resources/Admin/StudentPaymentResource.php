<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentPaymentResource extends JsonResource
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
            'new_credit' => $this->studentPayment?->new_credit . '',
            'price_hr' => $this->classroom->subscriptions()->first()->price_hr,
            'user' => new UserBasicResource($this->user),
            'classroom' => new ClassroomBasicResource($this->classroom),
        ];
    }
}
