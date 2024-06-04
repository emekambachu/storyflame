<?php

namespace App\Engine\Storage;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Story;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

abstract class DatabaseStorage extends BaseStorage implements StorageInterface
{
    public function __construct(
        string $uid
    )
    {
        $this->uid = $uid;
        if ($this->getUserId() !== auth()->id())
            throw new \Exception('Invalid conversation id');
    }

    abstract protected function getChat(): Chat;

    abstract protected function getModel(): Model;

    abstract static protected function initFromCache(CacheStorage $storage): static;

    public function getUserId(): string
    {
        return $this->getChat()->sender_id;
    }

    public static function fromCache(string $model_class, CacheStorage $cacheStorage): static
    {
        Log::info('migration from cache to database');
        /** @var DatabaseStorage $storage_class */
        $storage_class = match ($model_class) {
            Story::class => StoryDatabaseStorage::class,
            default => throw new \Exception('Unexpected model class to migrate'),
        };
        $instance = $storage_class::initFromCache($cacheStorage);

        Log::info('saving data', $cacheStorage->getData());

        $instance->setData($cacheStorage->getData());

        Log::info('saving history', $cacheStorage->getHistory());

        foreach ($cacheStorage->getHistory() as $message) {
            $instance->push($message);
        }

        return $instance;
    }

    public function getHistory(): array
    {
        return $this->getChat()->chatMessages()->orderBy('created_at', 'asc')->get()->all();
    }

    public function push(ChatMessage $message): void
    {
        $this->getChat()->chatMessages()->save($message);
    }

    final public function getIdentifier(): string
    {
        return static::IDENTIFIER_PREFIX . $this->getModel()->id;
    }

    final public function setIdentifier(string $uid): void
    {
        throw new \Exception('Cannot change identifier of a database storage.');
    }

    public function getExtractedData(): array
    {
        return $this->getChat()->extra_attributes['extracted'] ?? [];
    }

    public function saveExtractedData(array $extracted): void
    {
        $chat = $this->getChat();
        $chat->extra_attributes['extracted'] = array_merge($chat->extra_attributes['extracted'] ?? [], $extracted);
        $chat->save();
    }

    public function getData(): array
    {
        return $this->getChat()->extra_attributes?->toArray() ?? [
            'user_id' => auth()->id(),
            'messages' => [],
            'extracted' => [],
            'queue' => [],
            'branches' => [],
            'created_at' => now(),
        ];
    }

    public function setData(array $data): void
    {
        $chat = $this->getChat();
        Log::info('setting data to chat', $chat->toArray());
        $chat->extra_attributes = $data;
        $chat->save();
    }
}
