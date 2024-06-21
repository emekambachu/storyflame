<?php

namespace App\Services\Category;

use App\Models\Category;

class CategoryService
{

    public function category(): Category
    {
        return new Category();
    }

}
