<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Story extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'user_id',
    ];

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
