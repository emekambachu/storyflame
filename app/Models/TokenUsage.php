<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TokenUsage extends Model
{
    use HasUuids;

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
