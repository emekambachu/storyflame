<?php

namespace App\Models\Chat;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Concerns\HasSchemalessAttributes;
use App\Models\UserAchievement;
use App\Observers\SessionChatObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

#[ObservedBy(SessionChatObserver::class)]
class SessionChat extends Model
{
    use SoftDeletes, HasSchemalessAttributes;

    protected $fillable = [
        'chat_id',
        'target_type',
        'target_id',
        'summary',
        'persistent',
        'last_message_at',
        'finished_at',
        'summarized_at'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function target(): BelongsTo
    {
        return $this->morphTo();
    }

    public function userAchievements(): BelongsToMany
    {
        return $this->belongsToMany(UserAchievement::class, 'session_chat_user_achievements')
            ->withTimestamps();
    }

    public function sessionElements(): HasMany
    {
        return $this->hasMany(SessionChatElement::class);
    }

    public function chatMessages(): BelongsToMany
    {
        return $this->belongsToMany(ChatMessage::class, 'session_chat_chat_messages')
            ->withTimestamps();
    }

    /**
     * Finish the session chat
     * @return void
     */
    public function finish(): void
    {
        Log::info('Finishing session chat', ['session_chat_id' => $this->id]);
        $this->finished_at = now();
        $this->save();
    }

    /**
     * Restart the session chat
     * @return void
     */
    public function restart(): void
    {
        Log::info('Restarting session chat', ['session_chat_id' => $this->id]);
        $this->finished_at = null;
        $this->summarized_at = null;
        $this->save();
    }
}
