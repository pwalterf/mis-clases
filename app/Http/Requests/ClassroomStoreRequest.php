<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:classrooms,name'],
            'description' => ['nullable', 'string'],
            'price_hr' => ['required', 'numeric', 'between:0,9999.99'],
            'started_at' => ['required', 'date'],
            'students' => ['nullable', 'array'],
            'students.*.credit' => ['sometimes', 'required', 'numeric'],
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
            'name' => 'Nombre',
            'description' => 'Descripción',
            'price_hr' => 'Precio por Hora',
            'started_at' => 'Fecha Suscripción',
            'students' => 'Alumnos',
            'students.*.credit' => 'Créditos',
        ];
    }
}
