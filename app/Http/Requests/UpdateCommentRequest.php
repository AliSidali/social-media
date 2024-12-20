<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $comment = $this->route('comment');
        return auth()->user()->id === $comment->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => ['string', 'required'],
            "parent_id" => ['integer'],
            "attachment" => [
                'file',
                "max:1048576",
                File::types(['jpg', 'jpeg', 'png', 'gif', 'webp']),
            ],
            "deletedAttachmentId" => ['integer']
        ];
    }
}
