<?php

namespace App\Engine\Storage;

use App\Models\Chat;
use App\Models\Story;

class StoryDatabaseStorage extends DatabaseStorage
{
    protected const IDENTIFIER_PREFIX = 'story_';

    private Story $model;

    protected function getChat(): Chat
    {
        $model = $this->getModel();
        if ($chat = $model->chats()->where('type', 'story')->first()) {
            return $chat;
        }

        return $model->chats()->create([
            'type' => 'story',
            'sender_id' => $model->user_id,
        ]);
    }

    protected function getModel(): Story
    {
        if (!isset($this->model)) {
            $this->model = Story::findOrFail($this->uid);
        }
        return $this->model;
    }

    protected static function initFromCache(CacheStorage $storage): static
    {
        $story = Story::create([
            'name' => $storage->getExtractedData()['title'] ?? 'Untitled',
            'user_id' => $storage->getUserId(),
            'created_at' => $storage->getData()['created_at'],
        ]);
        $instance = new static($story->id);
        $storage->pushQueue([
            'type' => 'change_storage',
            'from' => $storage->getIdentifier(),
            'to' => $instance->getIdentifier()
        ]);
        return $instance;
    }
}
