<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            //'dni' => ['required', 'numeric', 'digits_between:7,9', 'unique:users,dni,' . $this->user->id],
            //'birthdate' => ['required', 'date'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'role' => ['required', 'array'],
            'role.*' => ['required', 'numeric', 'exists:roles,id'],
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
            'firstname' => 'Nombre',
            'lastname' => 'Apellido',
            //'dni' => 'DNI',
            //'birthdate' => 'Fecha de Nacimiento',
            'email' => 'Correo Electrónico',
            'role' => 'Rol',
        ];
    }
}
