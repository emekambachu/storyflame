<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends \Laravel\Paddle\Customer
{
    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        static ::creating(function ($customer) {
            // set the name because Paddle does not set it
            $customer->name = $customer->billable->name;
        });

        static::created(function ($customer) {
            $billable = $customer->billable;

            if ($billable) {
                // set the paddle_id on the billable model
                $billable->paddle_id = $customer->paddle_id;
                $billable->save();
            }
        });
    }

    /**
     * Get the billable model related to the customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function billable()
    {
        return $this->morphTo();
    }
}
