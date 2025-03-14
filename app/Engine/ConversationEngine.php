<?php

namespace App\Engine;

use App\Engine\Context\BaseContext;
use App\Engine\Context\ContextInterface;
use App\Engine\Processing\LocalPythonProcessing;
use App\Engine\Processing\MockProcessing;
use App\Engine\Processing\ProcessingInterface;
use App\Engine\Storage\BaseStorage;
use App\Engine\Storage\DatabaseStorage;
use App\Engine\Storage\StorageInterface;
use App\Jobs\DeepElementAnalysisJob;
use App\Models\Achievement;
use App\Models\ChatMessage;
use App\Models\DataPoint;
use App\Services\AchievementService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class ConversationEngine
{
    protected DatabaseStorage $storage;
    protected ProcessingInterface $processing;

    public final function __construct(
        string|null $storageIdentifier = null
    )
    {
        $this->processing = new LocalPythonProcessing();
        if ($storageIdentifier === null)
            $storageIdentifier = $this->getStoragePrefix();
        else if (!str_starts_with($storageIdentifier, $this->getStoragePrefix()))
            throw new Exception('Invalid storage identifier');
        $this->storage = DatabaseStorage::make($storageIdentifier);
    }

    public abstract function getStoragePrefix(): string;

    public function getProgress(): float
    {
        $topic = $this->getInitialTopic();
        $dataPoints = $topic->dataPoints->pluck('slug')->toArray();
        $extracted = $this->storage->getExtractedData();
        $extractedFromCurrentTopic = array_intersect_key($extracted, array_flip($dataPoints));
        return count($extractedFromCurrentTopic) / count($dataPoints) * 100;
    }

    private function arrayToKeyValues(string $prefix, array $array): array
    {
        $values = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $values = array_merge($values, $this->arrayToKeyValues($prefix . '.' . $key, $value));
            } else {
                $values["{{$prefix}.{$key}}"] = $value;
            }
        }
        return $values;
    }

    private function replaceTemplate(string $text): string
    {
        $replacements = array_merge(
            [
                '{app_name}' => config('app.name'),
            ],
            $this->arrayToKeyValues('extracted', $this->storage->getExtractedData())
        );
        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    public function initializeEngine()
    {
    }

    public function onStorageSet(DatabaseStorage $storage)
    {
    }

    public static function make(string $engine, string|null $storage = null): self
    {
        $engine = match ($engine) {
            'stories' => new StoryConversationEngine($storage),
            'onboarding' => new OnboardingConversationEngine($storage),
            'Character' => new CharacterConversationEngine($storage),
            default => throw new Exception('Engine not found'),
        };
        $engine->initializeEngine();
        return $engine;
    }

    /**
     * @param DatabaseStorage $storage
     * @return $this
     * @deprecated sets automatically
     */
    public function setStorage(DatabaseStorage $storage): static
    {
        $this->storage = $storage;
        $this->onStorageSet($storage);
        return $this;
    }

    /**
     * @throws Exception if uuid is invalid
     * @deprecated sets automatically
     */
    public function withCacheStorage(string|null $uuid = null): self
    {
//        $this->setStorage(new CacheStorage($uuid));
        return $this;
    }

    /**
     * @throws Exception if responses are empty
     */
    public function withMockResponses(array $responses): self
    {
        $this->processing = new MockProcessing($responses);
        return $this;
    }

    protected function rateAnswer(ChatMessage $question, ChatMessage $answer): array
    {
        $rating = $this->processing->rateResponse($question->content, $answer->content);
        $answer->extra_attributes->set('rating', $rating);
        return $rating;
    }

    protected function didAskedQuestionRecently(ChatMessage $question): bool
    {
        $questions = $this->getQuestionHistory();
        $dataPoints = $question->extra_attributes->get('data_points', []);
        if (empty($dataPoints)) {
            return false;
        }

        // count how many times data points have been asked
        $dataPointsCount = [];
        foreach ($questions as $question) {
            foreach ($question['extra_attributes']['data_points'] ?? [] as $dataPoint) {
                if (!isset($dataPointsCount[$dataPoint])) {
                    $dataPointsCount[$dataPoint] = 0;
                }
                $dataPointsCount[$dataPoint]++;
            }
        }

        // check if all data points from last question have been asked
        foreach ($dataPoints as $dataPoint) {
            if (!isset($dataPointsCount[$dataPoint])) {
                if ($dataPointsCount[$dataPoint] < 2) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function skipQuestion(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        foreach ($question->extra_attributes->get('data_points', []) as $dataPoint) {
            $this->storage->saveExtractedData([$dataPoint => null]);
        }
        return $this->generateNextQuestion($question, $answer);
    }

    public function extract(ChatMessage $question, ChatMessage $answer): array
    {
        return $this->processing->extractData($question->content, $answer->content, $this->getGroupedTopics(
            except_data_points: array_keys($this->storage->getExtractedData())
        ));
    }

    private function changeTopic(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        dd('changeTopic');
    }

    /**
     * Check if the topic is finished
     * @param Achievement $topic
     * @return bool
     */
    private function isTopicFinished(Achievement $topic): bool
    {
        $extracted = $this->storage->getExtractedData();
        $availableDataPoints = $topic->dataPoints->filter(function (DataPoint $dataPoint) use ($extracted) {
            return !in_array($dataPoint->slug, array_keys($extracted));
        });
        return $availableDataPoints->isEmpty();
    }

    private function getAvailableDataPoints(Achievement $topic): array
    {
        $extracted = $this->storage->getExtractedData();
        $availableDataPoints = $topic->dataPoints->filter(function (DataPoint $dataPoint) use ($extracted) {
            return !in_array($dataPoint->slug, array_keys($extracted));
        });
        return $availableDataPoints->map(function (DataPoint $dataPoint) {
            return $dataPoint->toProcessingArray();
        })->values()->toArray();
    }

    private function getCurrentBranch(): array
    {
        $currentBranch = $this->storage->getCurrentBranch();
        if (!$currentBranch) {
            $this->storage->setCurrentBranch($this->getInitialTopic(), 'main');
            return $this->storage->getCurrentBranch();
        }
        return $currentBranch;
    }

    private function getNextDataPoints(): array|ChatMessage
    {
        $currentBranch = $this->getCurrentBranch();
        if (!$currentBranch) {
            throw new Exception('No branch found');
        }
        if ($currentBranch['name'] === 'main')
            $achievement = $this->getInitialTopic();
        else
            $achievement = Achievement::firstWhere('slug', $currentBranch['topic']);

        $availableDataPoints = $this->getAvailableDataPoints($achievement);
        Log::info('availableDataPoints', $availableDataPoints);
        if (empty($availableDataPoints)) {
            if ($response = $this->onTopicFinished($achievement)) {
                return $response;
            }
            $this->storage->markBranchAsFinished();
            $finishedTopics = array_map(
                fn($branch) => $branch['topic'],
                array_filter(
                    $this->storage->getBranches(),
                    fn($branch) => $this->isTopicFinished(Achievement::firstWhere('slug', $branch['topic']))
                )
            );
            $nextTopic = $this->getTopics()
                ->whereNotIn('slug', $finishedTopics)
                ->get()
                ->sort(fn($a, $b) => $a->totalImpactScore() <=> $b->totalImpactScore())
                ->first();

            if (!$nextTopic) {
                throw new Exception('No more topics');
            }
            $this->storage->setCurrentBranch($nextTopic);
            return $this->getAvailableDataPoints($nextTopic);
        }
        return $availableDataPoints;
    }

    /**
     * Get history of the conversation between user and AI
     * Ready for processing
     * @return array
     */
    private function getChatHistory(): array
    {
        // return last 10 messages
        return array_map(
            function (ChatMessage $message) {
                return [
                    'agent' => $message->user_id === null ? 'assistant' : 'user',
                    'content' => $message->content,
                ];
            },
            array_slice(
                array_filter(
                    $this->storage->getHistory(),
                    fn($message) => $message->type !== 'system'
                ),
                -10
            )
        );
    }

    abstract public function getEngineName(): string;

    private function generateQuestion(ChatMessage $previousQuestion, ChatMessage $previousAnswer, array $dataPoints, string $type = 'basic'): ChatMessage
    {
        $next = $this->processing->generateNextQuestion(
            $this->getEngineName(),
            $this->getChatHistory(),
            $dataPoints,
            $type
        );
        $branch = $this->getCurrentBranch();
        return ChatMessage::make([
            'id' => 'cache_' . Str::uuid(),
            'type' => 'text',
            'content' => $next['question'],
            'extra_attributes' => [
                'title' => $next['message'],
                'data_points' => $next['data_points'],
                'branch' => $branch['name'],
                'topic' => $branch['topic']
            ]
        ]);
    }

    private function generateNextQuestion(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        $nextDataPointsOrResponse = $this->getNextDataPoints();
        if ($nextDataPointsOrResponse instanceof ChatMessage) {
            return $nextDataPointsOrResponse;
        }
        return $this->generateQuestion(
            $question,
            $answer,
            $nextDataPointsOrResponse,
            'basic'
        );
    }

    protected function generateFollowUp(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        return $this->generateQuestion(
            $question,
            $answer,
            DataPoint::whereIn('slug', $question->extra_attributes?->get('data_points') ?? [])
                ->get()->map(function (DataPoint $dataPoint) {
                    return $dataPoint->toProcessingArray();
                })->values()->toArray(),
            'follow-up'
        );
    }

    protected function generateBrainstorm(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        return $this->generateQuestion(
            $question,
            $answer,
            DataPoint::whereIn('slug', $question->extra_attributes->get('data_points'))
                ->get()->map(function (DataPoint $dataPoint) {
                    return $dataPoint->toProcessingArray();
                })->values()->toArray(),
            'brainstorm'
        );
    }

    protected abstract function getElement(): string;

    protected abstract function getInitialTopic(): Achievement;

    /**
     * @return ChatMessage[]|null
     */
    protected abstract function getPredefinedQuestions(): ?array;

    private function getLastQuestionGroup(): ?string
    {
        $history = array_filter(
            $this->getQuestionHistory(),
            fn($message) => isset($message['extra_attributes']['group'])
        );
        if (empty($history)) {
            return null;
        }
        return end($history)['extra_attributes']['group'];
    }

    private function getNextPredefinedQuestion(): ?ChatMessage
    {
        if ($predefinedQuestions = $this->getPredefinedQuestions()) {
            $lastGroup = $this->getLastQuestionGroup();
            if (!$lastGroup) {
                $firstGroupKey = array_key_first($predefinedQuestions);
                $firstGroup = $predefinedQuestions[$firstGroupKey];
                return ChatMessage::make([
                    'id' => 'cache_' . Str::uuid(),
                    'type' => 'text',
                    'content' => $this->replaceTemplate($firstGroup[0]['question']),
                    'extra_attributes' => [
                        'group' => $firstGroupKey,
                        'title' => $this->replaceTemplate($firstGroup[0]['title']),
                        'data_points' => $firstGroup[0]['data_points'] ?? [],
                    ]
                ]);
            }
            $lastGroupIndex = array_search($lastGroup, array_keys($predefinedQuestions));
            $nextGroupKey = array_keys($predefinedQuestions)[$lastGroupIndex + 1] ?? null;
            if ($nextGroupKey) {
                $nextGroup = $predefinedQuestions[$nextGroupKey];
                return ChatMessage::make([
                    'id' => 'cache_' . Str::uuid(),
                    'type' => 'text',
                    'content' => $this->replaceTemplate($nextGroup[0]['question']),
                    'extra_attributes' => [
                        'group' => $nextGroupKey,
                        'title' => $this->replaceTemplate($nextGroup[0]['title']),
                        'data_points' => $nextGroup[0]['data_points'] ?? []
                    ]
                ]);
            }
        }
        return null;
    }

    /**
     * Get all topics for the current element
     * @return Builder
     */
    protected function getTopics(): Builder
    {
        return Achievement::query()->where('element', $this->getElement());
    }

    private function getGroupedTopics(array $except_topics = [], array $except_data_points = []): array
    {
        return $this->getTopics()
            ->whereNotIn('slug', $except_topics)
            ->with(['dataPoints'])
            ->get()
            ->map
            ->toProcessingArray($except_data_points)
            ->toArray();
    }

    /**
     * @param string $content Question content
     * @param string $title Question title
     * @param array $dataPoints Data points slugs
     * @param string $topic Achievement slug
     * @param string|null $group Group name if any
     * @param string $branch Branch name, default is 'main'
     * @return ChatMessage
     */
    public function pushQuestion(string $content, string $title, array $dataPoints, string $topic, string $group = null, string $branch = 'main'): ChatMessage
    {
        $chatMessage = ChatMessage::make([
            'id' => 'cache_' . Str::uuid(),
            'type' => 'text',
            'content' => $content,
            'extra_attributes' => [
                'title' => $title,
                'topic' => $topic,
                'data_points' => $dataPoints,
                'group' => $group,
                'branch' => $branch
            ]
        ]);
        $this->pushMessageObject($chatMessage);
        return $chatMessage;
    }

    public function pushMessageObject(ChatMessage $message): void
    {
        if ($this->processing instanceof MockProcessing) {
            $this->processing->simulateDelay();
        }
        $this->storage->push($message);
    }

    private function generateFirstQuestion(): ChatMessage
    {
        $initialQuestion = $this->getNextPredefinedQuestion();
        $topic = $this->getInitialTopic();
        if (!$initialQuestion) {
            // generate first question
            throw new Exception('Logic to generate first question not implemented yet');
        }
        $initialQuestion->extra_attributes->set('topic', $topic->slug);
        $initialQuestion->extra_attributes->set('branch', 'main');

        $this->pushMessageObject($initialQuestion);
        $this->storage->setCurrentBranch($topic, 'main');
        return $initialQuestion;
    }

    private function getQuestionHistory(): array
    {
        return array_filter(
            $this->storage->getHistory(),
            fn($message) => $message['user_id'] === null
        );
    }

    public function getLastQuestion(): ChatMessage
    {
        $history = $this->getQuestionHistory();
        if (empty($history)) {
            return $this->generateFirstQuestion();
        }
        return end($history);
    }

    private function processTextAnswer(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        $rating = $this->rateAnswer($question, $answer);

        // user did answer the question
        if ($rating['answer_rating']) {
            $extracted = $this->extract($question, $answer);
            $extracted = $this->onDataExtracted($answer, $extracted);
            dump($extracted);
            $answer->extra_attributes->set('extracted', $extracted);
            $this->storage->saveExtractedData($extracted);
            DeepElementAnalysisJob::dispatch(
                $question,
                $answer,
                $this,
                $extracted
            );

            // if user want to change topic
            if ($rating['topic_change']) {
                $this->pushMessageObject($answer);
                return $this->changeTopic($question, $answer);
            }

            // if extracted data is not empty
            // generate next question
            // otherwise, skip or generate follow up
            if (count($extracted) !== 0) {
                $this->pushMessageObject($answer);
                if ($generated = $this->getNextPredefinedQuestion()) {
                    return $generated;
                }
                if ($queuedMessage = $this->storage->popQuestionQueue()) {
                    return $queuedMessage;
                }
                return $this->generateNextQuestion($question, $answer);
            }
        }

        $this->pushMessageObject($answer);

        if ($this->didAskedQuestionRecently($question)) {
            return $this->skipQuestion($question, $answer);
        }

        // user not understand
        // re-phrase the question
        if ($rating['user_not_understand']) {
            return $this->generateFollowUp($question, $answer);
        }

        // user don't know
        // generate brainstorm
        if ($rating['user_dont_know']) {
            return $this->generateBrainstorm($question, $answer);
        }

        if ($rating['is_skipped']) {
            return $this->skipQuestion($question, $answer);
        }

        return $this->generateFollowUp($question, $answer);
    }

    private function processMultipleChoiceAnswer(ChatMessage $question, ChatMessage $answer): ChatMessage
    {

    }

    private function processQueue()
    {
        while ($item = $this->storage->popQueue()) {
            switch ($item['type']) {
                case 'change_storage':
                    $this->setStorage(BaseStorage::make($item['to']));
                    break;
                default:
                    throw new Exception('Not implemented ' . $item['type'] . ' type');
            }
        }
    }

    protected function processConfirmSwitchElement(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        $positive = true;
        if ($positive) {
            $engine = ConversationEngine::make($question->extra_attributes->get('element'));
            dd('here');
        }
    }

    public function process(string $answer): string
    {
        $this->processQueue();

        $question = $this->getLastQuestion();
        $answer = ChatMessage::make([
            'id' => 'cache_' . Str::uuid(),
            'type' => 'text',
            'user_id' => auth()->id(),
            'content' => $answer,
        ]);

        $question = match ($question->type) {
            'text' => $this->processTextAnswer($question, $answer),
            'multiple_choice' => $this->processMultipleChoiceAnswer($question, $answer),
            'confirm_switch_element' => $this->processConfirmSwitchElement($question, $answer),
            default => throw new Exception('Not implemented ' . $question->type . ' type'),
        };

        $this->pushMessageObject($question);

        $this->onAnswerProcessed($question, $answer);
        $this->updateAchievementsProgress($this->storage->getExtractedData());

        return $question->content;
    }

    public function getIdentifier(): string
    {
        return $this->storage->getIdentifier();
    }

    public function extractEverything(string $question, string $answer, array $except): array
    {
    }

    public function getStorage(): DatabaseStorage
    {
        return $this->storage;
    }

    public function getProcessing(): ProcessingInterface
    {
        return $this->processing;
    }

    public function setProcessing(ProcessingInterface $processing): static
    {
        $this->processing = $processing;
        return $this;
    }

    /**
     * Modify extracted data if needed before saving
     * @param ChatMessage $answer
     * @param array $extracted
     * @return array
     */
    protected function onDataExtracted(ChatMessage $answer, array $extracted): array
    {
        return $extracted;
    }

    protected function updateAchievementsProgress(array $extracted): void
    {
        AchievementService::updateProgress(
            auth()->user(),
            $extracted,
            Achievement::where('element', $this->getElement())->get(),
            auth()->user(),//todo: change target to the correct model
        );
    }

    protected function onAnswerProcessed(ChatMessage $question, ChatMessage $answer)
    {
    }

    protected function onTopicFinished(Achievement $achievement): ?ChatMessage
    {
        return null;
    }

    public function extractCategories(ChatMessage $question, ChatMessage $answer): array
    {
        return $this->processing->extractCategories(
            $question->content,
            $answer->content,
        );
    }

    public function getContext(): ContextInterface
    {
        return BaseContext::make($this->storage->getModel());
    }
}
