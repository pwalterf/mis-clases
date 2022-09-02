<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class LessonBasicResource extends JsonResource
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
            'comment' => $this->comment ? Str::limit($this->comment, 30) : 'Ninguno',
            'student_page' => $this->student_page ?? '-',
            'workbook_page' => $this->workbook_page ?? '-',
            'lesson_date' => $this->lesson_date->format('d/m/Y'),
        ];
    }
}
