<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicPostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function posts(Request $request)
    {
        $posts = Post::with(['author', 'category'])
            ->when($request->search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return PublicPostResource::collection($posts);
    }
}
