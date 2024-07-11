<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionItem extends \Laravel\Paddle\SubscriptionItem
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'price_id',
        'product_id',
        'product_price_id',
        'status',
        'quantity',
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
