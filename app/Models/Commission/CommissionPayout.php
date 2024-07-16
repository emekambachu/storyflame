<?php

namespace App\Models\Commission;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionPayout extends Model
{
    use HasFactory;
    protected $fillable = [
        'referrer_id',
        'number_of_transactions',
        'amount',
        'commission_inventory_earliest_date',
        'commission_inventory_latest_date',
        'paid_at',
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }
}
