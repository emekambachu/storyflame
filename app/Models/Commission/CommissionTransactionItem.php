<?php

namespace App\Models\Commission;

use App\Models\Referral\ReferralReward;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionTransactionItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'referrer_id',
        'referral_eward_id',
        'transaction_id',
        'transaction_earnings',
        'commission_amount',
        'commission_payout_id',
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    public function referral_reward(): BelongsTo
    {
        return $this->belongsTo(ReferralReward::class, 'referral_reward_id', 'id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function commission_payout(): BelongsTo
    {
        return $this->belongsTo(CommissionPayout::class, 'commission_payout_id', 'id');
    }
}
