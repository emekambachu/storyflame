<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paddle_id',
        'name',
        'type',
        'description',
        'benefits',
        'details',
        'status'
    ];

    protected $casts = [
        'benefits' => 'array',
    ];

    public function subscriptionItems()
    {
        return $this->hasMany(SubscriptionItem::class, 'product_id', 'paddle_id');
    }

    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }
}
