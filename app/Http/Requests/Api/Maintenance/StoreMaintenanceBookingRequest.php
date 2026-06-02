<?php

namespace App\Http\Requests\Api\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We will use auth:sanctum middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'maintenance_service_id' => 'required|exists:maintenance_services,id',
            'phone' => [
                'required',
                'string',
                'regex:/^01[0125][0-9]{8}$/',
            ],
            'address' => 'required|string|max:255',
            'message' => 'nullable|string|max:1000',
        ];
    }
}
