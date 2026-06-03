<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->letters()
                    ->numbers(),
            ],
            'role' => 'nullable|in:buyer,seller',
            'phone' => [
                'required',
                'string',
                'regex:/^01[0125][0-9]{8}$/',
            ],
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_photo' => 'nullable|required_if:role,seller|image|mimes:jpg,jpeg,png|max:2048',
            'governorate_id' => 'nullable|exists:governorates,id',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => __('validation.custom.email.unique'),
            'role.in' => __('validation.custom.role.in'),
            'phone.required_if' => __('validation.custom.phone.required_if'),
            'phone.regex' => __('validation.custom.phone.regex'),
            'address.required_if' => __('validation.custom.address.required_if'),
            'password.min' => __('validation.min.string', ['min' => 8]),
            'password.letters' => __('validation.password.letters'),
            'password.numbers' => __('validation.password.numbers'),
        ];
    }
}
