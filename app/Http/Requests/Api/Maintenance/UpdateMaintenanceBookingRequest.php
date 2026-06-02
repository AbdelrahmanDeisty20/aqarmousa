<?php

namespace App\Http\Requests\Api\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => [
                'nullable',
                'string',
                'regex:/^01[0125][0-9]{8}$/',
            ],
            'address' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
        ];
    }
}
