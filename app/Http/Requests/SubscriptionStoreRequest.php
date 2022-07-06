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
        //dd($this->classroom->subscriptions->first());
        return [
            'price_hr' => [
                'required', 'numeric', 'between:0,9999.99',
                function ($attribute, $value, $fail) {
                    if ($value === $this->classroom->subscriptions->first()->price_hr) {
                        $fail('El '.$attribute.' es idéntico.');
                    }
                },
            ],
        ];
    }
}
