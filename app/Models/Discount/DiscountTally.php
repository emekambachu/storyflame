<?php

namespace App\Models\Discount;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountTally extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'commission_id',
        'is_used'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
