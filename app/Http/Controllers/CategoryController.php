<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->get();
        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        if (!auth()->user()->can('category-manage')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category = Category::create($request->validated());

        return response()->json([
            'message' => 'Category created successfully',
            'category' => new CategoryResource($category),
        ], 201);
    }

    public function show(Category $category)
    {
        $category->loadCount('posts');
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        if (!auth()->user()->can('category-manage')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category->update($request->validated());

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => new CategoryResource($category),
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        if (!auth()->user()->can('category-manage')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($category->posts()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with existing posts'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
