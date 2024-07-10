<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Paddle\Cashier;
use Laravel\Paddle\Concerns\Prorates;
use Laravel\Paddle\Payment;

class Subscription extends \Laravel\Paddle\Subscription
{
    use HasFactory, Prorates;

    protected $fillable = [
        'type',
        'paddle_id',
        'status',
        'trial_ends_at',
        'paused_at',
        'ends_at',
        'next_billed_at'
    ];

    protected $dates = [
        'trial_ends_at',
        'paused_at',
        'ends_at',
        'next_billed_at'
    ];

    /**
     * Get the subscription items for the subscription.
     */
    public function items()
    {
        return $this->hasMany(SubscriptionItem::class);
    }
}
