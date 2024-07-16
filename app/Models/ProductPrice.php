<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Paddle\Cashier;
use Money\Currency;

class ProductPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paddle_id',
        'product_id',
        'name',
        'description',
        'price',
        'currency_code',
        'status',
        'interval',
        'interval_frequency',
        'included_reports',
        'included_images',
    ];

    public function subscriptionItems()
    {
        return $this->hasMany(SubscriptionItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the amount.
     *
     * @return string
     */
    public function amount()
    {
        return Cashier::formatAmount($this->rawAmount(), $this->currency());
    }

    /**
     * Get the raw amount.
     *
     * @return string
     */
    public function rawAmount()
    {
        return $this->price;
    }

    /**
     * Get the interval for the price.
     *
     * @return string|null
     */
    public function interval()
    {
        return $this->interval ?? null;
    }

    /**
     * Get the frequency for the price.
     *
     * @return int|null
     */
    public function frequency()
    {
        return $this->interval_frequency ?? null;
    }

    /**
     * Get the used currency for the price.
     *
     * @return \Money\Currency
     */
    public function currency(): Currency
    {
        return $this->currency_code;
    }
}
