<?php

namespace App\Http\Controllers;

use App\Http\Enums\RoleEnum;
use App\Http\Enums\StatusEnum;
use App\Http\Requests\StoreGroupRequest;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function store(StoreGroupRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $group = Group::create($data);
        GroupUser::create([
            "status" => StatusEnum::APPROVED->value,
            "role" => RoleEnum::ADMIN->value,
            "user_id" => $user->id,
            "group_id" => $group->id,
            "created_by" => $group->user_id
        ]);
        return response($group, 201);
    }
}
