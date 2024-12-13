<?php

namespace App\Http\Requests;

use App\Rules\TotalFilesSize;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rules\File;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public static $extensions = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp',
        'mp3',
        'wav',
        'mp4',
        'doc',
        'docx',
        'csv',
        'xls',
        'xlsx',
        'zip',
    ];
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
            'attachments' => ['array', 'max:50'],
            'attachments.*' => [
                'file',
                File::types(
                    self::$extensions
                )->max(500 * 1024 * 1024 * 1024)
            ],
            'body' => ['string'],

        ];

    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->id(),
            'body' => $this->input('body') ?? '',
        ]);
    }

    public function messages()
    {
        return [
            'attachments.*.mimes' => 'invalid file'
        ];
    }


}
