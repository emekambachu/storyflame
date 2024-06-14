<?php

namespace App\Engine\Storage;

use App\Models\Character;
use App\Models\Chat;
use App\Models\Story;

class CharacterDatabaseStorage extends DatabaseStorage
{
    protected const IDENTIFIER_PREFIX = 'character_';

    public function __construct(string|null $uid)
    {
        if ($uid && $uid !== '') {
            $character = Character::findOrFail($uid);
            if ($character->story->user_id !== auth()->id()) {
                throw new \Exception('Invalid conversation id');
            }
        } else {
            $character = Character::create([
                'name' => null,
                'user_id' => auth()->id(),
            ]);
            $uid = $character->id;
        }
        $this->model = $character;
        parent::__construct($uid);
    }

    private Character $model;

    protected function getChat(): Chat
    {
        $model = $this->getModel();
        if ($chat = $model->story->chats()->first()) {
            return $chat;
        }

        return $model->story->chats()->create([
            'type' => 'story',
            'sender_id' => $model->user_id,
        ]);
    }

    public function getModel(): Character
    {
        if (!isset($this->model)) {
            $this->model = Character::findOrFail($this->uid);
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
