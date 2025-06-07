<?php

namespace App\Http\Controllers;

use App\Exports\CategoriesExport;
use App\Exports\PostsExport;
use App\Imports\CategoriesImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function exportCategories()
    {
        if (!auth()->user()->can('category-manage')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }

    public function exportPosts()
    {
        if (!auth()->user()->can('post-edit')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return Excel::download(new PostsExport, 'posts.xlsx');
    }

    public function importCategories(Request $request): JsonResponse
    {
        if (!auth()->user()->can('category-manage')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new CategoriesImport, $request->file('file'));

            return response()->json([
                'message' => 'Categories imported successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Import failed: ' . $e->getMessage()
            ], 422);
        }
    }
}
