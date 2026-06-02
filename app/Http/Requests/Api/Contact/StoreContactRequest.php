<?php

namespace App\Http\Requests\Api\Contact;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->unit_id) {
                $unit = \App\Models\Unit::find($this->unit_id);
                if ($unit) {
                    if ($unit->status === 'sold') {
                        $validator->errors()->add('unit_id', __('api.unit_already_sold'));
                    } elseif ($unit->status === 'rented') {
                        $validator->errors()->add('unit_id', __('api.unit_already_rented'));
                    }
                }
            }
        });
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => [
                'required',
                'string',
                'regex:/^01[0125][0-9]{8}$/',
            ],
            'address' => 'nullable|string|max:500',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'unit_id' => 'nullable|exists:units,id',
            'seller_id' => 'nullable|exists:users,id',
        ];
    }
}
