<?php

namespace App\Models;

use App\Models\Concerns\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use SoftDeletes, HasUuids, HasFactory, HasSchemalessAttributes;

    protected $fillable = [
        'type',
        'user_id',
        'chat_id',
        'content',
        'extra_attributes',
        'created_at',
        'updated_at',
    ];

    public function scopeNotSystem($query)
    {
        return $query->where('type', '!=', 'system');
    }

    public function scopeFromAssistant($query)
    {
        return $query->whereNull('user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voiceMessage(): HasOne
    {
        return $this->hasOne(ChatVoiceMessage::class);
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function achievement()
    {
        return $this->belongsToMany(Achievement::class, 'achievement_chat_message');
    }

    public function expectsConfirmation()
    {
        return str_starts_with($this->type, 'confirm_');
    }

    public function dataPoints()
    {
        return $this->belongsToMany(DataPoint::class, 'chat_message_data_point');
    }

    public function toProcessingArray()
    {
        return [
            'agent' => $this->user_id ? 'user' : 'assistant',
            'content' => $this->content,
        ];
    }
}
