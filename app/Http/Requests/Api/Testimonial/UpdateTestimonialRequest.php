<?php

namespace App\Http\Requests\Api\Testimonial;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
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
            'position' => 'nullable|string|max:255',
            'content'  => 'nullable|string',
            'status'   => 'nullable|boolean', // Admin only usually, but service handles user scope
        ];
    }
}
