<?php

namespace App\Models\Referral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCommission extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'referrer_id',
        'recipient_id',
        'amount',
        'percentage',
        'transaction_id',
        'paid_at',
    ];
}
