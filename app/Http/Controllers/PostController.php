<?php

namespace App\Http\Controllers;

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
}
