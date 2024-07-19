<?php

namespace App\Models\Referral;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'priority',
        'is_active',
        'description',
        'commission_percent',
        'referrer_discount_percent',
        'recipient_discount_percent',
    ];

    public function referrers(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'user_referrals', 'referrer_id', 'user_id');
    }

    public function recipients(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'user_referrals', 'recipient_id', 'user_id');
    }

    public function dateRanges(): HasMany
    {
        return $this->hasMany(ReferralTypeDateRange::class, 'referral_type_id','id');
    }

}
