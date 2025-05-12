<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function followUser(Request $request, User $user)
    {
        $data = $request->validate([
            'isCurrentUserFollower' => ['boolean']
        ]);
        $authUser = auth()->user();


        if ($data['isCurrentUserFollower']) {
            $authUser->followedUsers()->detach($user->id);
            $notification_message = "User " . $authUser->username . " has unfollowed you";
            $message = "you unfollowed user " . $user->username;


        } else {
            $authUser->followedUsers()->attach($user->id, [
                'created_at' => now()
            ]);
            $message = "you follow user " . $user->username;
            $notification_message = "User " . $authUser->username . " has followed you";

        }

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Follow User: ',
            'message' => $notification_message,
            'notificable_id' => $authUser->id,
            'notificable_type' => User::class

        ]);

        return back()->with('success', $message);
    }
}
