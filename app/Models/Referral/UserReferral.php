<?php

namespace App\Models\Referral;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReferral extends Model
{
    use HasFactory;
    protected $fillable = [
        'referrer_id',
        'recipient_id',
        'referral_type_id',
        'recipient_discount_amount',
        'referrer_discount_amount',
        'referrer_commission_amount',
        'discount_ends_at',
        'commission_ends_at',
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id', 'id');
    }

    public function referral_type(): BelongsTo
    {
        return $this->belongsTo(ReferralType::class, 'referral_type_id', 'id');
    }
}
