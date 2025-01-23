<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Support\Str;
use App\Http\Enums\RoleEnum;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Enums\StatusEnum;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GroupResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreGroupRequest;
use App\Notifications\InvitationToGroup;
use App\Http\Requests\InviteUsersRequest;
use App\Notifications\InvitationApproved;

class GroupController extends Controller
{

    public function profile(Group $group)
    {
        return Inertia::render('Group/Profile', [
            'group' => new GroupResource($group),
            'message' => ['success' => session('success')],
            'pendingUsers' => UserResource::collection($group->getPendingUsers()),
            'approvedUsers' => UserResource::collection($group->getApprovedUsers())
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
        $invitedUser = $request->user;

        if ($invitedUser->pivot?->token_expire_date < Carbon::now()) {
            $group->users()->detach($invitedUser->id);
        }
        $token = Str::random(256);
        $group->users()->attach($invitedUser->id, [
            'status' => StatusEnum::PENDING->value,
            'role' => RoleEnum::USER->value,
            'token' => $token,
            'token_expire_date' => Carbon::now()->addHours(24),
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

    public function join(Request $request, Group $group)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $groupUserExists = $group->users()->wherePivot('user_id', $user->id)->exists();
            if ($groupUserExists) {
                dd('yella');
            }


            $status = StatusEnum::PENDING->value;
            $message = 'your request is send successfully';

            //for notifications table
            $notification_title = "joining the group " . $group->name;
            $notification_message = $user->username . " send a request to join the group " . $group->name;


            if ($group->auto_approval) {
                //for group_user table
                $status = StatusEnum::APPROVED->value;

                //for notifications table
                $notification_message = $user->username . " is joining the group " . $group->name;

                //for the redirection message
                $message = 'you have joined the group "' . $group->name . '"';
            }

            //create user group
            $group->users()->attach($user->id, [
                'role' => RoleEnum::USER->value,
                'status' => $status,
            ]);

            //send a notification of sending request or joining group
            Notification::create([
                'user_id' => $group->user_id,
                'title' => $notification_title,
                'message' => $notification_message,
                'notificable_id' => $user->id,
                'notificable_type' => User::class

            ]);
            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
        }

        return back()->with('success', $message);
    }

    public function groupApproval(Request $request, Group $group)
    {
        DB::beginTransaction();
        try {
            $currentUser = auth()->user();
            $groupCurrentUser = $group->users()->wherePivot('user_id', $currentUser->id)->first();
            if ($groupCurrentUser->pivot->role !== RoleEnum::ADMIN->value) {
                return back();
            }

            $groupUser = $group->users()->wherePivot('user_id', $request->userId)->first();
            $userRole = $groupUser->pivot->role;
            $userStatus = $groupUser->pivot->status;

            $status = StatusEnum::APPROVED->value;
            $message = 'your request is approved';
            if ($userRole == RoleEnum::ADMIN->value) {
                return back();
            } elseif ($userStatus == StatusEnum::APPROVED->value) {
                $status = StatusEnum::PENDING->value;
                $message = 'your request is disapproved';
            }

            //send approved and disapproved notifications
            Notification::create([
                'user_id' => $request->userId,
                'title' => 'response of the request',
                'message' => $message,
                'is_read' => false,
                'notificable_id' => $group->id,
                'notificable_type' => Group::class
            ]);


            $group->users()->updateExistingPivot($request->userId, ['status' => $status]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return back();
    }


    //for notifications
    public function readNotifications()
    {
        $user = auth()->user();
        $notReadNotificationsExists = $user->receivedNotifications()->where('is_read', false)->exists();
        if (!$notReadNotificationsExists) {
            return response(null, 404);
        }
        $notReadNotifications = $user->receivedNotifications()->where('is_read', false)->update(['is_read' => true]);

        return response(null, 201);
    }
}
