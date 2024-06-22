<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Base\BaseService;
use App\Services\Category\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $category;
    public function __construct(CategoryService $category)
    {
        $this->category = $category;
    }

    public function index(): JsonResponse
    {
        try {
            $data = $this->category->category()->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'categories' => $data,
            ]);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->category->storeCategory($request);
            return response()->json($data);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $data = $this->category->updateCategory($request);
            return response()->json($data);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function destroy(Request $request): JsonResponse
    {
        try {
            $data = $this->category->deleteCategory($request);
            return response()->json($data);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }
}
