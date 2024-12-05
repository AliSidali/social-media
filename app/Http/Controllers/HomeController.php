<?php

namespace App\Http\Controllers;

use App\Http\Enums\StatusEnum;
use App\Http\Resources\GroupResource;
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
        $posts = Post::query()
            ->with('attachments')
            ->with('reactions')
            ->latest()
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
            'groups' => GroupResource::collection($groups)
        ]);
    }

    public function errorPage(Request $request)
    {
        return Inertia::render('error', [
            'text' => $request->text
        ]);
    }
}
