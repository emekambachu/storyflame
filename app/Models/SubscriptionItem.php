<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionItem extends \Laravel\Paddle\SubscriptionItem
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_price_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'paddle_id');
    }

    public function productPrice()
    {
        return $this->belongsTo(ProductPrice::class, 'price_id', 'paddle_id');
    }
}
