<?php

namespace App\Http\Resources\Classroom;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class LessonResource extends JsonResource
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
            'comment' => Str::limit($this->comment, 30),
            'student_page' => $this->student_page,
            'workbook_page' => $this->workbook_page,
            'lesson_date' => $this->lesson_date->format('d/m/Y'),
        ];
    }
}
