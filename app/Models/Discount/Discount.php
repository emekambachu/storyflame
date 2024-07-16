<?php

namespace App\Models\Discount;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'status',
        'description',
        'enabled_for_checkout',
        'code',
        'type',
        'amount',
        'currency_code',
        'recur',
        'maximum_recurring_intervals',
        'usage_limit',
        'restrict_to',
        'expires_at',
        'times_used',
        'custom_data',
        'import_meta',
    ];

    protected $casts = [
        'enabled_for_checkout' => 'boolean',
        'recur' => 'boolean',
        'expires_at' => 'datetime',
        'custom_data' => 'array',
        'import_meta' => 'array',
    ];
}
