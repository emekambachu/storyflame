<?php

namespace App\Engine\Storage;

use App\Models\Chat;
use App\Models\User;

class OnboardingDatabaseStorage extends DatabaseStorage
{
    protected const IDENTIFIER_PREFIX = 'onboarding_';

    private User $model;

    public function __construct(string $uid)
    {
        parent::__construct($uid);
        $chat = $this->getChat();
    }

    protected function getChat(): Chat
    {
        $model = $this->getModel();
        if ($chat = $model->chats()->where('type', 'onboarding')->first()) {
            return $chat;
        }

        return $model->chats()->create([
            'type' => 'onboarding',
            'sender_id' => $model->user_id,
        ]);
    }

    protected function getModel(): User
    {
        if (!isset($this->model)) {
            $this->model = User::findOrFail($this->uid);
        }
        return $this->model;
    }

    protected static function initFromCache(CacheStorage $storage): static
    {
        $user = auth()->user();
        $instance = new static($user->id);
        $storage->pushQueue([
            'type' => 'change_storage',
            'from' => $storage->getIdentifier(),
            'to' => $instance->getIdentifier()
        ]);
        return $instance;
    }
}
