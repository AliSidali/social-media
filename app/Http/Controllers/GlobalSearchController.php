<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $search = $request->keyword;

        if ($search) {

            $posts = Post::query()
                ->where('body', 'like', "%$search%")
                ->with('user')
                ->latest()
                ->paginate(5);

            if ($request->page) {
                return response(['posts' => $posts]);
            }

            $users = User::query()
                ->whereAny(['name', 'username'], 'like', '%' . $search . '%')
                ->get();

            $groups = Group::query()
                ->whereAny(['name', 'slug'], 'like', '%' . $search . '%')
                ->get();



            return response([
                'users' => $users ? UserResource::collection($users) : null,
                'groups' => $groups ? GroupResource::collection($groups) : null,
                'posts' => $posts
            ]);
        }



    }
}
