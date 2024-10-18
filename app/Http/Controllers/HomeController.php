<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query()
            ->latest()
            ->paginate(5);
        $posts = PostResource::collection($posts);
        if ($request->wantsJson()) {
            return $posts;
        }
        return Inertia::render('Home', [
            'success' => session('success'),
            'posts' => $posts
        ]);
    }
}
