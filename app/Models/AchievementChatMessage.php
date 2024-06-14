<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AchievementChatMessage extends Pivot
{
    protected $fillable = [
        'achievement_id',
        'chat_message_id',
    ];

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }

    public function chatMessage()
    {
        return $this->belongsTo(ChatMessage::class);
    }
}
