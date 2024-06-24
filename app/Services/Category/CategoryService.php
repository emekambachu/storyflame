<?php

namespace App\Services\Category;

use App\Models\Category;

class CategoryService
{

    public function category(): Category
    {
        return new Category();
    }

    public function storeCategory($request): array
    {
        $inputs = $request->all();
        $category = $this->category()->create($inputs);
        return [
            'success' => true,
            'category' => $category,
        ];
    }

    public function updateCategory($request): array
    {
        $inputs = $request->all();
        $category = $this->category()->find($request->id);
        $category->update($inputs);

        return [
            'success' => true,
            'category' => $category,
        ];
    }

    public function deleteCategory($request): array
    {
        $category = $this->category()->find($request->id);
        $category->delete();

        return [
            'success' => true,
        ];
    }

}
