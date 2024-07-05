<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SessionChatElement extends Model
{
    protected $fillable = [
        'session_chat_id',
        'element_type',
        'element_id',
        'action',
    ];

    public function sessionChat(): BelongsTo
    {
        return $this->belongsTo(SessionChat::class);
    }

    public function element(): MorphTo
    {
        return $this->morphTo();
    }
}
