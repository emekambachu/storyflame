<?php

namespace App\Models\Referral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReferral extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'referral_type_id'
    ];
}
