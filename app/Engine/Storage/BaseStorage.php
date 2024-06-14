<?php

namespace App\Engine\Storage;

use App\Models\Achievement;
use App\Models\ChatMessage;
use Exception;
use Illuminate\Support\Str;

abstract class BaseStorage implements StorageInterface
{
    protected const IDENTIFIER_PREFIX = '';

    protected string $uid;

    protected function init()
    {
        $data = $this->getData();
        $this->setData([
            'user_id' => auth()->id(),
            'extracted' => $data['extracted'] ?? [],
            'messages' => $data['messages'] ?? [],
            'branches' => $data['branches'] ?? [],
            'queue' => $data['queue'] ?? [],
            'created_at' => $data['created_at'] ?? now(),
        ]);
    }

    /**
     * @throws Exception if the storage id is invalid
     */
    public static function make(string $uid = null): StorageInterface
    {
        $explode = explode('_', $uid);
        $storage = match ($explode[0]) {
            'cache' => new CacheStorage($explode[1]),
            'story' => new StoryDatabaseStorage($explode[1]),
            'onboarding' => new OnboardingDatabaseStorage($explode[1]),
            default => throw new Exception('Invalid storage type ' . $explode[0]),
        };
        $storage->init();
        return $storage;
    }

    public function getIdentifier(): string
    {
        return static::IDENTIFIER_PREFIX . $this->uid;
    }

    public function setIdentifier(string $uid): void
    {
        // queue change storage event
        // so that we can update front-end that the identifier has changed
        $this->pushQueue([
            'type' => 'change_storage',
            'from' => $this->uid,
            'to' => $uid,
        ]);
        $this->uid = $uid;
    }

    public function getCurrentBranch(): bool|array
    {
        $branches = $this->getBranches();
        $unfinished = array_filter($branches, fn($branch) => !$branch['finished']);
        return end($unfinished);
    }

    public function setCurrentBranch(Achievement $topic, string $branch_name = null): void
    {
        $data = $this->getData();
        $branches = $this->getBranches();
        $currentBranch = $this->getCurrentBranch();
        $branches[] = [
            'id' => $topic->slug . '_' . Str::uuid(),
            'name' => $branch_name ?? $topic->name,
            'parent_id' => $currentBranch['id'] ?? null,
            'topic' => $topic->slug,
            'finished' => false,
        ];
        $data['branches'] = $branches;
        $this->setData($data);
    }

    public function markBranchAsFinished(): void
    {
        $data = $this->getData();
        $branches = $data['branches'];
        $currentBranch = $this->getCurrentBranch();
        if (!$currentBranch) {
            return;
        }
        if ($currentBranch['topic'] === 'main') {
            return;
        }
        $currentBranchIndex = array_search($currentBranch, $branches);
        $branches[$currentBranchIndex]['finished'] = true;
        $data['branches'] = $branches;
        $this->setData($data);
    }

    public function pushQueue(array $array): int
    {
        $data = $this->getData();
        $count = array_unshift($data['queue'], $array); // Add to the beginning of the queue (FIFO)
        $this->setData($data);
        return $count;
    }

    public function popQueue(): array|bool
    {
        $data = $this->getData();
        $queue = $data['queue'];
        if (empty($queue)) {
            return false;
        }
        $item = array_pop($queue); // Remove from the end of the queue (FIFO)
        $data['queue'] = $queue;
        $this->setData($data);
        return $item;
    }

    public function queueQuestion(array $message): int
    {
        $data = $this->getData();
        if (!isset($data['messages_queue'])) {
            $data['messages_queue'] = [];
        }
        $count = array_unshift($data['messages_queue'], $message); // Add to the end of the queue (FIFO)
        $this->setData($data);
        return $count;
    }

    public function popQuestionQueue(): ChatMessage|bool
    {
        $data = $this->getData();
        $queue = $data['messages_queue'];
        if (empty($queue)) {
            return false;
        }
        $item = array_pop($queue); // Remove from the end of the queue (FIFO)
        $data['messages_queue'] = $queue;
        $this->setData($data);
        return ChatMessage::make($item);
    }

    public function getExtractedData(): array
    {
        return $this->getData()['extracted'];
    }

    public function getHistory(): array
    {
        return $this->getData()['messages'];
    }

    public function getBranches(): array
    {
        return $this->getData()['branches'] ?? [];
    }
}
