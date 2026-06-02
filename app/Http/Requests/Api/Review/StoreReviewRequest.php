<?php

namespace App\Http\Requests\Api\Review;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unit_id' => [
                'required',
                'exists:units,id',
                'unique:reviews,unit_id,NULL,id,user_id,' . $this->user()->id
            ],
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validation
     */
    public function messages(): array
    {
        return [
            'unit_id.unique' => __('api.review.already_reviewed'),
        ];
    }
}
