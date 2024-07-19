<?php

namespace App\Models\Referral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralTypeDateRange extends Model
{
    use HasFactory;
    protected $fillable = [
        'referral_type_id',
        'starts_at',
        'ends_at',
        'month_duration',
    ];

    public function referralType(): BelongsTo
    {
        return $this->belongsTo(ReferralType::class, 'referral_type_id', 'id');
    }
}
