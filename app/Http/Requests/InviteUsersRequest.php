<?php

namespace App\Http\Requests;

use App\Http\Enums\RoleEnum;
use App\Models\User;
use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class InviteUsersRequest extends FormRequest
{
    public ?User $user;
    public Group $group;
    public $groupUser;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->group = $this->route('group');
        return $this->group->isCurrentUserAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                function ($attribute, $value, \Closure $fail) {
                    $this->user = User::query()->where($attribute, $value)
                        ->orWhere('username', $value)->first();
                    if (!$this->user) {
                        $fail(__('messages.user_existence'));
                        return;
                    }
                    $this->groupUser = $this->group->users()->wherePivot('user_id', $this->user->id)->first();
                    if ($this->groupUser && $this->groupUser->pivot->token_expire_date > Carbon::now()) {
                        $fail(__('messages.already_invited'));
                    }
                }
            ]
        ];
    }
}
