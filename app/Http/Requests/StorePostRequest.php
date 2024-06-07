<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'user_id' => '',
            'body' => ['nullable', 'string'],
        ];

    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->id(),
            'body' => $this->input('body') ?: '',
        ]);
    }

}
