<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => ['sometimes', 'string', 'max:255'],
            'content' => ['sometimes', 'string'],
            'locale' => ['sometimes', 'string', 'max:10'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['string']
        ];
    }
}
