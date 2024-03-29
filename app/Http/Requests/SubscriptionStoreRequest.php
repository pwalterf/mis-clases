<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionStoreRequest extends FormRequest
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
            'price_hr' => [
                'required', 'numeric', 'between:0,9999.99',
                function ($attribute, $value, $fail) {
                    if ($value == $this->classroom->subscriptions->first()?->price_hr) {
                        $fail('El Precio por Hora es idéntico.');
                    }
                },
            ],
            'started_at' => ['required', 'date'],
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
            'price_hr' => 'Precio por Hora',
            'started_at' => 'Fecha Suscripción',
        ];
    }
}
