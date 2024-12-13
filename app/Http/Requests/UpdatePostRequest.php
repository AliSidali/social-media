<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Validation\Rules\File;
use App\Http\Requests\StorePostRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends StorePostRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = $this->route('post');
        return $post->user_id === auth()->user()->id;
    }

    public function rules(): array
    {
        $rules = parent::rules();
        $additionnalRules = [
            'deleted_file_ids' => ['array'],
            'deleted_file_ids.*' => ['numeric']
        ];
        return array_merge($rules, $additionnalRules);
    }



}
