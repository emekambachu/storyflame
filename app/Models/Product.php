<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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
        return $this->hasMany(SubscriptionItem::class);
    }

    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class);
    }
}
