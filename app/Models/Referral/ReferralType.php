<?php

namespace App\Models\Referral;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReferralType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'priority',
        'is_active',
        'description',
        'starts_at',
        'ends_at',
        'commission_percent',
        'discount_percent',
    ];

    public function referrers(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'user_referrals', 'referrer_id', 'user_id');
    }

    public function recipients(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'user_referrals', 'recipient_id', 'user_id');
    }

}
