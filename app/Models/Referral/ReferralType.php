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
        'description'
    ];

    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'user_referrals', 'referral_type_id', 'user_id');
    }
}
