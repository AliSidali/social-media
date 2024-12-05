<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\InvitationApproved;
use Inertia\Inertia;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Support\Str;
use App\Http\Enums\RoleEnum;
use Illuminate\Http\Request;
use App\Http\Enums\StatusEnum;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GroupResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreGroupRequest;
use App\Notifications\InvitationToGroup;
use App\Http\Requests\InviteUsersRequest;

class GroupController extends Controller
{

    public function profile(Group $group)
    {
        return Inertia::render('Group/Profile', [
            'group' => new GroupResource($group),
            'message' => ['success' => session('success')]
        ]);
    }
    public function store(StoreGroupRequest $request)
    {
        $user = auth()->user();

        $data = $request->validated();
        $data['user_id'] = $user->id;

        $group = Group::create($data);
        $group->users()->attach($user->id, [
            "status" => StatusEnum::APPROVED->value,
            "role" => RoleEnum::ADMIN->value,
            "created_by" => $group->user_id
        ]);
        $group->status = StatusEnum::APPROVED->value;
        $group->role = RoleEnum::ADMIN->value;
        return response(["group" => new GroupResource($group)], 201);
    }

    public function updateImage(Request $request, Group $group)
    {
        if (!$group->isCurrentUserAdmin()) {
            return response("You don't have permission to make this action!", 403); //forbiden
        }
        $data = $request->validate([
            'cover' => ['image', 'nullable'],
            'thumbnail' => ['image', 'nullable'],
        ]);

        $paths = [];
        if ($request->cover) {
            if ($group->cover_path) {
                Storage::disk('public')->delete($group->cover_path);
            }
            $paths['cover_path'] = $request->file('cover')->store('groups/group-' . $group->id . '/cover', 'public');
            $success = 'Your cover image has been updated.';
        }
        if ($request->thumbnail) {
            if ($group->thumbnail_path) {
                Storage::disk('public')->delete($group->thumbnail_path);
            }
            $paths['thumbnail_path'] = $request->file('thumbnail')->store('groups/group-' . $group->id . '/thumbnail', 'public');
            $success = 'Your thumbnail image has been updated.';
        }

        $group = $group->update($paths);
        return response(['success' => $success]);

    }

    public function inviteUser(InviteUsersRequest $request, Group $group)
    {
        $data = $request->validated();
        $invitedUser = $request->groupUser;

        if ($invitedUser->pivot->token_expire_date < Carbon::now()) {
            $group->users()->detach($invitedUser->id);
        }
        $token = Str::random(256);
        $group->users()->attach($invitedUser->id, [
            'status' => StatusEnum::PENDING->value,
            'role' => RoleEnum::USER->value,
            'token' => $token,
            'token_expire_date' => Carbon::now()->addMinute(),
            'created_by' => Auth::id()
        ]);

        $invitedUser->notify(new InvitationToGroup($group, $token));

        return back()->with('success', 'You have been sent invitation to ' . $invitedUser->username);
    }

    public function approveInvitation(Request $request, Group $group)
    {

        $text = '';
        $GroupUserWithPivot = $group->users()->wherePivot('token', $request->token)->first();
        $expireDate = $GroupUserWithPivot->pivot->token_expire_date;
        if (!$GroupUserWithPivot) {
            $text = "The token is not valid!";
        } elseif ($GroupUserWithPivot->pivot->token_used || $GroupUserWithPivot->pivot->status === StatusEnum::APPROVED->value) {
            $text = "the link is used";
        } else
            if (Carbon::now() > $expireDate) {
                $text = "the link is expired!";
            }

        if ($text) {
            return inertia('Error', [
                'text' => $text
            ]);
        }

        $group->users()->updateExistingPivot($GroupUserWithPivot->pivot->user_id, [
            'token_used' => Carbon::now(),
            'status' => StatusEnum::APPROVED->value,
            'role' => RoleEnum::ADMIN->value
        ]);
        $GroupUserWithPivot->notify(new InvitationApproved($GroupUserWithPivot, $group));
        return redirect()->route('group.profile', $group->slug)->with('success', 'Welcome ' . $GroupUserWithPivot->name . ', you  joined the group ' . $group->name);

    }
}
