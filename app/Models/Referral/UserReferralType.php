<?php

namespace App\Models\Referral;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReferralType extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'referral_type_id',
//        'start_date',
//        'end_date',
//        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function referral_type(): BelongsTo
    {
        return $this->belongsTo(ReferralType::class, 'referral_type_id', 'id');
    }
}
