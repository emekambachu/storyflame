<?php

namespace App\Engine;

use App\Engine\Storage\BaseStorage;
use App\Engine\Storage\CacheStorage;
use App\Engine\Storage\StorageInterface;
use App\Models\Achievement;
use App\Models\ChatMessage;
use App\Services\AchievementService;
use App\Services\ProcessingService;

class OnboardingConversationEngine extends ConversationEngine
{

    public static function generateBio(\App\Models\User $user)
    {
        $user->extra_attributes->set(
            'bio',
            ProcessingService::generateTextData($user->extra_attributes->toArray(), 'bio')
        );
    }

    protected function getInitialTopic(): Achievement
    {
        return Achievement::firstWhere('slug', 'writer_identity');
    }

//    protected function getInitialQuestion(): ?string
//    {
//        return 'What is your name?';
//    }
    public function onStorageSet(StorageInterface $storage)
    {
        // override any storage with onboarding storage
        if ($storage instanceof CacheStorage)
            $this->setStorage(
                BaseStorage::make('onboarding_' . auth()->id())
            );
    }


    protected function getElement(): string
    {
        return 'Writer';
    }

    protected function onAnswerProcessed(ChatMessage $question, ChatMessage $answer)
    {
//        AchievementService::updateProgress(auth()->user(), $this->storage->getExtractedData());
    }

    public function withCacheStorage(?string $uuid = null): ConversationEngine
    {
        return $this->setStorage(
            BaseStorage::make('cache_u' . auth()->id())
        );
    }


    protected function getPredefinedQuestions(): ?array
    {
        return [
            'intro' => [
                [
                    'question' => "What's your name?",
                    'title' => "Hi! I'm StoryFlame.",
                    'data_points' => [
                        'name'
                    ]
                ],
            ],
            'big-picture' => [
                [
                    'question' => 'Tell me a little about yourself as a writer.',
                    'title' => 'Nice to meet you, {extracted.name}.'
                ],
            ],
        ];
    }

    public function getEngineName(): string
    {
        return 'onboarding';
    }

    protected function onDataExtracted(ChatMessage $answer, array $extracted): array
    {
        if (array_key_exists('name', $extracted)) {
            $user = auth()->user();
            $user->name = $extracted['name'];
            $user->save();
        }
        return $extracted;
    }

    protected function onTopicFinished(Achievement $achievement): ?ChatMessage
    {
        $user = auth()->user();
        $user->extra_attributes = $this->storage->getExtractedData();
        $user->extra_attributes->set('onboarded', true);
        $user->save();
        return ChatMessage::make([
            'type' => 'system',
            'content' => 'finish'
        ]);
    }

    public function getStoragePrefix(): string
    {
        return 'onboarding_';
    }
}
