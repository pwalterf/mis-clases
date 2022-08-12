<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentUpdateRequest extends FormRequest
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
            'payment_date' => ['required', 'date'],
            'income' => ['required', 'numeric', 'between:0,99999,99'],
            'comment' => ['nullable', 'string'],
            'students' => ['required', 'array'],
            'students.*.new_credit' => ['sometimes', 'required', 'numeric'],
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
            'payment_date' => 'Fecha del Pago',
            'income' => 'Ingreso',
            'comment' => 'Comentario',
            'students' => 'Alumnos',
            'students.*.new_credit' => 'Créditos',
        ];
    }
}
