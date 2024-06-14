<?php

namespace App\Engine;

use App\Engine\Storage\BaseStorage;
use App\Engine\Storage\CacheStorage;
use App\Engine\Storage\StorageInterface;
use App\Engine\Storage\StoryDatabaseStorage;
use App\Models\Achievement;
use App\Models\ChatMessage;
use App\Models\Story;
use App\Services\ProcessingService;
use Illuminate\Support\Facades\Log;

class StoryConversationEngine extends ConversationEngine
{
    public function onStorageSet(StorageInterface $storage)
    {
        if ($storage instanceof CacheStorage) {
//            $this->setStorage(
//                BaseStorage::make('story_' . )
//            );
        }
    }


    protected function getInitialTopic(): Achievement
    {
        return Achievement::firstWhere('slug', 'story_fundamentals');
    }

    protected function getElement(): string
    {
        return 'Story';
    }

    public function getStoragePrefix(): string
    {
        return 'story_';
    }


    protected function onAnswerProcessed(ChatMessage $question, ChatMessage $answer)
    {
        $data = $this->storage->getExtractedData();

        if (!empty($data)) {
            if ($this->storage instanceof CacheStorage) {
                Log::info('storage is still cache, migrating');
                dispatch(function () use ($data) {
                    $storage = BaseStorage::make($this->getIdentifier());
                    if ($storage instanceof CacheStorage) {
                        if (!isset($data['title'])) {
                            Log::info('title is not set, generating');
                            $title = ProcessingService::generateTextData($data, 'title');
                            $this->storage->saveExtractedData(['title' => $title]);
                        }
                        StoryDatabaseStorage::fromCache(Story::class, $storage);
                    }
                })->afterResponse();
            }
        }
    }

    public function getEngineName(): string
    {
        return 'story';
    }

    protected function getPredefinedQuestions(): ?array
    {
        return [
            'intro' => [
                [
                    'question' => 'What is your story about?',
                    'title' => "Let's start with your story!",
                ]
            ]
        ];
    }
}
