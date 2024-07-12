<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\Product\UserProduct;

class ProductService
{
    public function product(): Product
    {
        return new Product();
    }

    public function userProduct(): UserProduct
    {
        return new UserProduct();
    }

    public function addProductToUser($membershipName, $userId): void
    {
        $productId = $this->product()->where('name', $membershipName)->first()->id;
        $this->userProduct()->create([
            'product_id' => $productId,
            'user_id' => $userId
        ]);
    }

}
