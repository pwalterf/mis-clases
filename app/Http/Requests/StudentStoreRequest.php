<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
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
            'classroom_id' => ['required', 'numeric'],
            'user_id' => ['required', 'numeric'],
            'credit' => ['sometimes', 'required', 'numeric', 'integer'],
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
            'classroom_id' => 'Clase',
            'user_id' => 'Alumno',
            'credit' => 'Créditos'
        ];
    }
}
