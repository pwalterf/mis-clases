<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'lesson_date' => ['required', 'date'],
            'comment' => ['required', 'string'],
            'student_page' => ['nullable', 'string'],
            'workbook_page' => ['nullable', 'string'],
            'classroom_id' => ['required', 'integer', 'exists:App\Models\Classroom,id'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'lesson_date' => 'Fecha de la Lección',
            'comment' => 'Comentario',
            'student_page' => 'Libro Estudiante',
            'workbook_page' => 'Libro Ejercicios',
            'classroom_id' => 'Clase',
        ];
    }
}
