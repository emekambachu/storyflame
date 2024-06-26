<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenUsage extends Model
{
    protected $fillable = [
        'key',
        'user_id',
        'target_id',
        'target_type',
        'model',
        'input_tokens',
        'output_tokens',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
