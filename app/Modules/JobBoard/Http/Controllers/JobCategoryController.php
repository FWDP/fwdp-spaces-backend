<?php

namespace App\Modules\JobBoard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\JobBoard\Models\JobCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(JobCategory::withCount('listings')->orderBy('name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:job_categories',
            'description' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);

        return response()->json(JobCategory::create($data), 201);
    }

    public function update(Request $request, int $categoryId): JsonResponse
    {
        $category = JobCategory::findOrFail($categoryId);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255|unique:job_categories,name,'.$category->id,
            'description' => 'nullable|string',
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return response()->json(JobCategory::find($category->id));
    }

    public function destroy(int $categoryId): JsonResponse
    {
        $category = JobCategory::findOrFail($categoryId);
        $category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }
}
