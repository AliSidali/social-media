<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttachmentResource;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */


    public function index(Request $request, User $user)
    {
        $authUser = auth()->user();
        if (!Auth::guest()) {
            $isFollowing = $authUser->followedUsers()->wherePivot('followed_id', $user->id)->exists();
        }

        $posts = Post::where('user_id', $user->id)
            ->with([
                'attachments',
                'reactions',
                'user',
                'post_comments' => function ($query) {
                    $query->with('reactions');
                }
            ])
            ->leftJoin('pin_posts as pp', function ($query) {
                $query->on('pp.post_id', 'posts.id')
                    ->where('pinable_type', User::class);
            })->select('posts.*', 'pp.id as pin_id')
            ->orderBy('pin_id', 'desc')
            ->latest()
            ->paginate(5);


        if ($request->wantsJson()) {
            return PostResource::collection($posts);
        }

        $followers = User::query()
            ->join('follower_users as f', 'f.follower_id', '=', 'users.id')
            ->select('users.*')
            ->where('f.followed_id', $user->id)
            ->get();

        $followings = User::query()
            ->select('users.*')
            ->join('follower_users as f', 'f.followed_id', '=', 'users.id')
            ->where('f.follower_id', $user->id)
            ->get();

        $attachments = Post::query()
            ->join('attachments', function ($query) {
                $query->on('posts.id', 'attachments.attachable_id')
                    ->where('attachments.attachable_type', Post::class)
                    ->where('mime', 'like', 'image/%');
            })
            ->select('attachments.*')
            ->where('user_id', $user->id)
            ->latest()
            ->get();



        return Inertia::render(
            'Profile/Index',
            [
                "user" => new UserResource($user),
                'status' => session('status'),
                'success' => session('success'),
                'isCurrentUserFollower' => $isFollowing ?? false,
                'posts' => PostResource::collection($posts),
                //'followers' => $user->followers,
                'followers' => $followers,
                'followings' => $followings,
                'attachments' => AttachmentResource::collection($attachments),
            ]
        );
    }
    // public function edit(Request $request): Response
    // {
    //     return Inertia::render('Profile/Edit', [
    //         'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
    //         'status' => session('status'),
    //     ]);
    // }


    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.index', $request->user())->with('success', 'Your profile details were updated.');

    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateImages(Request $request)
    {

        $user = auth()->user();
        $images = $request->validate([
            'cover' => ['nullable', 'image'],
            'avatar' => ['nullable', 'image']
        ]);

        $cover = $images['cover'] ?? null;
        $avatar = $images['avatar'] ?? null;

        $success = '';

        if ($cover) {
            if (auth()->user()->cover_path) {
                Storage::disk('public')->delete(auth()->user()->cover_path);
            }
            $cover_path = $cover->store('user-' . $user->id, 'public');  //store the image in storage folder
            $user->update(['cover_path' => $cover_path]);
            $success = 'Your cover image has been updated.';
        }
        if ($avatar) {
            if (auth()->user()->avatar_path) {
                Storage::disk('public')->delete(auth()->user()->avatar_path);
            }
            $avatar_path = $avatar->store('user-' . $user->id, 'public');
            $user->update(['avatar_path' => $avatar_path]);
            $success = 'Your avatar image has been updated.';
        }

        return back()->with('success', $success);

    }



}
