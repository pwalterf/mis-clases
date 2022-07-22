<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonEditResource extends JsonResource
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
            'comment' => $this->comment,
            'student_page' => $this->student_page,
            'workbook_page' => $this->workbook_page,
            'lesson_date' => $this->lesson_date->format('Y-m-d'),
            'classroom' => new ClassroomStudentsResource($this->classroom),
        ];
    }
}
