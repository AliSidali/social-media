<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->latest()
            ->paginate(20);
        return Inertia::render('Home', [
            'success' => session('success'),
            'posts' => PostResource::collection($posts)
        ]);
    }
}
