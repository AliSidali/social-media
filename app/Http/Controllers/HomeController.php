<?php

namespace App\Http\Controllers;

use App\Http\Enums\StatusEnum;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        // $following_ids = $user->followedUsers->pluck('id');
        // $group_ids = $user->groups->pluck('id');
        $posts = Post::query()
            // ->where(function ($query) use ($following_ids, $group_ids) {
            //     $query->whereIn('user_id', $following_ids)        // the commented block is correct, lets try another method
            //         ->orWhereIn('group_id', $group_ids);
            // })
            ->leftJoin(
                'follower_users as fu',
                function ($query) use ($user) {
                    $query->on('fu.followed_id', 'posts.user_id')
                        ->where('fu.follower_id', $user->id);
                }

            )
            ->leftJoin('group_user as gu', function ($query) use ($user) {
                $query->on('gu.group_id', 'posts.group_id')
                    ->where('gu.user_id', $user->id)
                    ->where('gu.status', StatusEnum::APPROVED->value);
            })
            ->select('posts.*', 'gu.group_id as gu_group_id', 'fu.followed_id')
            ->whereNotNull('fu.followed_id')
            ->orWhereNotNull('gu.group_id')
            ->orWhere('posts.user_id', $user->id)

            ->with(
                [
                    'attachments',
                    'reactions',
                    'post_comments',
                    'group',
                    'user'
                ]
            )
            ->latest()
            //  ->get();
            ->paginate(5);

        $posts = PostResource::collection($posts);
        if ($request->wantsJson()) {
            return $posts;
        }
        // $groups = Group::query()
        //     //->select(['groups.*', 'gu.role', 'gu.status'])
        //     ->with('groupUser')
        //     ->join('group_users as gu', 'groups.id', '=', 'gu.group_id')
        //     ->where('gu.user_id', $user->id)
        //     ->orderBy("gu.role")
        //     ->orderBy("name")
        //     ->get();

        $groups = Group::with([
            'users'
        ])->whereHas(
                'users',
                function ($query) {
                    $query->where('user_id', Auth::id());
                }
            )->get();

        return Inertia::render('Home', [
            'success' => session('success'),
            'posts' => $posts,
            'groups' => GroupResource::collection($groups),
            'language' => __('messages.language'),
            'followings' => UserResource::collection($user->followedUsers)
        ]);
    }

    public function errorPage(Request $request)
    {
        return Inertia::render('error', [
            'text' => $request->text
        ]);
    }
}
