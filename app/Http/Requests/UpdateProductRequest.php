<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'type' => 'sometimes|required|in:medication,vitamin,supplement,hygiene,beauty,others',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be greater than or equal to zero.',
            'type.required' => 'The product type is required.',
            'type.in' => 'The type must be one of the following: medication, vitamin, supplement, hygiene, beauty, or others.',
        ];
    }
}
