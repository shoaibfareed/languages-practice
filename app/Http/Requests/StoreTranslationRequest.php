<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'locale' => ['required', 'string', 'max:10'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['string']
        ];
    }
}