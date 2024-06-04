<?php

namespace App\Engine\Storage;

use App\Models\Achievement;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CacheStorage extends BaseStorage
{
    protected const IDENTIFIER_PREFIX = 'cache_';

    public function getUserId(): string
    {
        return $this->getData()['user_id'];
    }

    private function getAvailableId(): string
    {
        $id = Str::uuid();
        if (!Cache::has('conversation_engine.' . $id))
            return $id;
        return $this->getAvailableId();
    }

    public function __construct(
        string|null $uid = null
    )
    {
        if ($uid) {
            $this->uid = $uid;
            $this->init();
            if ($this->getData()['user_id'] !== auth()->id())
                throw new \Exception('Invalid conversation id');
        } else
            $this->uid = $this->getAvailableId();
    }

    public function saveExtractedData(array $extracted): void
    {
        $data = $this->getData();
        $data['extracted'] = array_merge($data['extracted'], $extracted);
        $this->setData($data);
    }

    public function getData(): array
    {
        return Cache::get('conversation_engine.' . $this->uid, fn() => [
            'user_id' => auth()->id(),
            'messages' => [],
            'extracted' => [],
            'queue' => [],
            'branches' => [],
            'created_at' => now(),
        ]);
    }

    /**
     * Function to save all the data to the storage
     * @param array $data
     * @return void
     */
    public function setData(array $data): void
    {
        Cache::put('conversation_engine.' . $this->uid, $data);
    }

    public function push(ChatMessage $message): void
    {
        $message->created_at = now();
        $message->updated_at = now();
        $data = $this->getData();
        $data['messages'][] = $message;
        $this->setData($data);
        Log::info('pushing message to cache', $this->getData());
    }
}
