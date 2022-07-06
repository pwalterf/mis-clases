<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
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
        ];
    }
}
