<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'regex:/[\w\.\-]+$/i'], //\w: for alphanumeric, \s: for space, +: contains  one or more, $:contains only this specific character, \i: case insensitive
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'cover_path' => ['image'],
            'avatar_path' => ['image'],

        ];
    }

    public function messages()
    {
        return [
            "regex" => "Username can only contain  alphanumeric characters, dots(.) and dashes(-)."
        ];
    }


}
