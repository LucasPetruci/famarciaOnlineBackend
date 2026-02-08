<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:medication,vitamin,supplement,hygiene,beauty,others',
            'sort_by' => 'sometimes|in:name,price',
            'sort_order' => 'sometimes|in:asc,desc',
        ];
    }
}
