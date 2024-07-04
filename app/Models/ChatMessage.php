<?php

namespace App\Models;

use App\Models\Chat\SessionChat;
use App\Models\Concerns\HasSchemalessAttributes;
use App\Observers\ChatMessageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ChatMessageObserver::class)]
class ChatMessage extends Model
{
    use SoftDeletes, HasFactory, HasSchemalessAttributes;

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

    public function sessionChats(): BelongsToMany
    {
        return $this->belongsToMany(SessionChat::class, 'session_chat_chat_messages')
            ->withTimestamps();
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

    /**
     * Make human message
     * TODO: possibly add type here
     * @param string $content
     * @return ChatMessage
     */
    public static function makeUserMessage(string $content): static
    {
        return static::make([
            'type' => 'text',
            'user_id' => auth()->id(),
            'content' => $content
        ]);
    }

    /**
     * @param string $type todo: replace by enum
     * @param string $content
     * @param string $title
     * @param array $data_points
     * @param array $extra
     * @return ChatMessage
     */
    public static function makeAiMessage(
        string $type,
        string $content,
        string $title,
        array  $data_points = [],
        array  $extra = []
    ): static
    {
        return static::make([
            'type' => $type,
            'user_id' => null,
            'content' => $content,
            'extra_attributes' => [
                'title' => $title,
                'data_points' => $data_points,
                ...$extra
            ]
        ]);
    }

    /**
     * @param string $content
     * @return static
     */
    public static function makeSystemMessage(
        string $content
    ): static
    {
        return static::make([
            'type' => 'system',
            'user_id' => null,
            'content' => $content
        ]);
    }

    public function getIsSystemAttribute(): bool
    {
        return $this->type === 'system';
    }

    public function getIsUserAttribute(): bool
    {
        return $this->user_id !== null;
    }

    public function getIsAssistantAttribute(): bool
    {
        return $this->user_id === null;
    }
}
