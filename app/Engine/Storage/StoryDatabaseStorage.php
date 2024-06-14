<?php

namespace App\Engine\Storage;

use App\Models\Chat;
use App\Models\Story;

class StoryDatabaseStorage extends DatabaseStorage
{
    protected const IDENTIFIER_PREFIX = 'story_';

    public function __construct(string|null $uid)
    {
        if ($uid && $uid !== '') {
            $story = Story::findOrFail($uid);
            if ($story->user_id !== auth()->id()) {
                throw new \Exception('Invalid conversation id');
            }
        } else {
            $story = Story::create([
                'name' => null,
                'user_id' => auth()->id(),
            ]);
            $uid = $story->id;
        }
        $this->model = $story;
        parent::__construct($uid);
    }

    private Story $model;

    protected function getChat(): Chat
    {
        $model = $this->getModel();
        if ($chat = $model->chats()->first()) {
            return $chat;
        }

        return $model->chats()->create([
            'type' => 'story',
            'sender_id' => $model->user_id,
        ]);
    }

    public function getModel(): Story
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
