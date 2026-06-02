<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreViewingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // User must be authenticated via middleware
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $unit = \App\Models\Unit::find($this->unit_id);

            if ($unit) {
                if ($unit->status === 'sold') {
                    $validator->errors()->add('unit_id', __('api.unit_already_sold'));
                } elseif ($unit->status === 'rented') {
                    $validator->errors()->add('unit_id', __('api.unit_already_rented'));
                }

                $exists = \App\Models\Viewing::where('unit_id', $this->unit_id)
                    ->where('user_id', $this->user()->id)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('unit_id', __('api.viewing.already_requested'));
                }
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => [
                'required',
                'string',
                'regex:/^01[0125][0-9]{8}$/',
            ],
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
