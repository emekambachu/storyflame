<?php

namespace App\Models\Referral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDiscount extends Model
{
    use HasFactory;
    protected $fillable = [
        'referrer_id',
        'recipient_id',
        'paddle_id',
        'amount',
        'description',
        'type',
        'percentage',
        'discount_ends_at',
    ];
}
