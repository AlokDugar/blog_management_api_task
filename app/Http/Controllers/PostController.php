<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with(['author', 'category'])
            ->when($request->search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->paginate(10);

        return PostResource::collection($posts);
    }

    public function store(PostRequest $request): JsonResponse
    {
        if (!auth()->user()->can('post-create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'category_id' => $request->category_id,
            'author_id' => auth()->id(),
        ]);

        $post->load(['author', 'category']);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => new PostResource($post),
        ], 201);
    }

    public function show(Post $post)
    {
        $post->load(['author', 'category']);
        return new PostResource($post);
    }

    public function update(PostRequest $request, Post $post): JsonResponse
    {
        if (!auth()->user()->can('post-edit') && auth()->id() !== $post->author_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->update($request->validated());
        $post->load(['author', 'category']);

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => new PostResource($post),
        ]);
    }

    public function destroy(Post $post): JsonResponse
    {
        if (!auth()->user()->can('post-delete') && auth()->id() !== $post->author_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }

    public function debug()
    {
        $user = auth()->user();
        return response()->json([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'can_edit_post' => $user->can('post-edit'),
            'can_delete_post' => $user->can('post-delete'),
            'can_manage_category' => $user->can('category-manage'),
        ]);
    }
}
