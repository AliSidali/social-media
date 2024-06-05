<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        $post = $request->validated();
        Post::create($post);
        return back()->with('success', 'post has been added.');
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());
        return back();
    }

    public function destroy(Post $post)
    {

        $post = Post::where('id', $post->id)->where('user_id', auth()->user()->id)->first();
        if (!$post) {
            return response("You don't have a permission to delete this post", 403);
        }

        $post->delete();
        return back();
    }
}
