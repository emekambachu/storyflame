<?php

namespace App\Engine\Context;

use App\Engine\Config\EngineConfig;
use App\Models\Achievement;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Concerns\ModelWithComparableNames;
use App\Models\Concerns\ModelWIthRelatedChats;
use App\Models\DataPoint;
use App\Models\Story;
use App\Models\StoryElements\Character;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserDataPoint;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * @template T of ModelWIthRelatedChats
 */
abstract class BaseContext implements ContextInterface
{
    protected Chat\SessionChat $sessionChat;

    /**
     * @return class-string<T>|null
     */
    abstract function getContextClass(): ?string;

    /**
     * @param Model|null $model
     */
    public function __construct(
        protected ?Model        $model = null,
        protected ?EngineConfig $config = null
    )
    {
        if (!$this->model) {
            if ($c = $this->getContextClass()) {
                $this->model = $c::create([
                    'user_id' => auth()->id(),
                ]);
            }
        }
        $this->sessionChat = Chat\SessionChat::where('target_id', $this->getModel()->id)
            ->where('target_type', get_class($this->getModel()))
            ->where('finished_at', null)
            ->firstOrCreate([
                'target_id' => $this->getModel()->id,
                'target_type' => get_class($this->getModel()),
            ], [
                'chat_id' => $this->getChat()->id,
                'persistent' => $this->config ? $this->config::SESSION_CHAT_PERSISTENT : false
            ]);
    }

    public function getChat(): Chat
    {
        // TODO: change this to latest session chat
        if (!($model = $this->getModel())) throw new Exception('Model is not set');

        if ($model->exists) {
            if ($this->getModel()->chats()->count() === 0) {
                $chat = Chat::create([
                    'type' => 'conversation',
                    'sender_id' => auth()->id(),
                ]);
                $this->getModel()->chats()->save($chat);
            }
            return $this->getModel()->chats()->first();
        } else {
            if (session()->has('temp_chat_id')) {
                return Chat::find(session()->get('temp_chat_id'));
            }
            $tempChat = Chat::create([
                'type' => 'temp',
                'sender_id' => auth()->id(),
            ]);
            session()->put('temp_chat_id', $tempChat->id);
            return $tempChat;
        }
    }


    protected function getSessionChat(): Chat\SessionChat
    {
        return $this->sessionChat;
//        $chat = $this->getChat();
//        return $chat->sessionChats()
//            ->whereNull('finished_at')
//            ->firstOrCreate([
//                'target_id' => $this->getModel()->id,
//                'target_type' => get_class($this->getModel()),
//            ], [
//                'persistent' => $this->config::SESSION_CHAT_PERSISTENT
//            ]);
    }

    public function getHistory(): array
    {
        return $this->getSessionChat()
            ->chatMessages()
            ->notSystem()
            ->oldest()
            ->get()
            ->map
            ->toProcessingArray()
            ->toArray();
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

    public function replaceTemplate(string $text): string
    {
        $replacements = array_merge(
            [
                '{app_name}' => config('app.name'),
            ],
            $this->arrayToKeyValues('extracted', $this->getModel()->dataPointsToArray()),
        );
        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    public function getAskedDataPoints(): array
    {
        return $this->getSessionChat()->chatMessages()
            ->notSystem()
            ->fromAssistant()
            ->get()
            ->pluck('extra_attributes')
            ->map
            ->get('data_points', [])
            ->flatten()
            ->reduce(function ($carry, $item) {
                $carry[$item] = ($carry[$item] ?? 0) + 1;
                return $carry;
            }, collect([]))
            ->filter(fn($item) => $item > 3)
            ->toArray();
    }

    public function saveMessage(ChatMessage $chatMessage): ChatMessage
    {
        $session = $this->getSessionChat();
        $chatMessage->content = $this->replaceTemplate($chatMessage->content);
        $chatMessage->extra_attributes->put('title', $this->replaceTemplate($chatMessage->extra_attributes->get('title', '')));
        if ($chatMessage->extra_attributes->has('data_points') && count($messageDps = $chatMessage->extra_attributes->get('data_points')) > 0) {
            $dps = $session->extra_attributes->get('data_points', []);
            foreach ($messageDps as $dp) {
                $dps[$dp] = ($dps[$dp] ?? 0) + 1;
            }
            $session->extra_attributes->put('data_points', $dps);
            $session->save();
        }
        $chatMessage->chat_id = $session->chat_id;
        $chatMessage = $session->chat->chatMessages()->save($chatMessage);
        $session->chatMessages()->attach($chatMessage->id);
        $session->last_message_at = $chatMessage->created_at;
        $session->save();
        return $chatMessage;
    }


    /**
     * @param T $model
     * @return static
     */
    public function setModel($model): static
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return T
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param T $model
     * @return BaseContext
     * @throws Exception
     */
    public static function make($model): BaseContext
    {
        return (match (get_class($model)) {
            Story::class => new StoryContext($model),
            User::class => new UserContext($model),
            Character::class => new CharacterContext($model),
            default => throw new Exception('Unknown model type ' . $model::class)
        });
    }

    public function withConfig(EngineConfig $config): static
    {
        $this->config = $config;
        return $this;
    }

    public function getElements(string $elementType): array
    {
        return match ($elementType) {
            'Character' => $this->characters()?->get()->all(),
            'Story' => $this->stories()?->get()->all(),
            'Plot' => $this->plots()?->get()->all(),
//            'Sequence' => $this->sequences()->get()->all(),
            default => []
        } ?? [];
    }

    public function addElement(string $elementType, array $elementData): ?ModelWithComparableNames
    {
        $model = match ($elementType) {
            'Story' => $this->stories()?->create($elementData),
            'Character' => $this->characters()?->create($elementData),
            'Plot' => $this->plots()?->create($elementData),
            'Sequence' => $this->sequences()->create($elementData),
            'Theme' => $this->themes()->create($elementData),
            'Setting' => $this->settings()->create($elementData),
            default => null
        } ?? null;
        if ($model) {
            $this->getSessionChat()->sessionElements()->create([
                'action' => 'create',
                'element_type' => get_class($model),
                'element_id' => $model->id,
            ]);
        }

        return $model;
    }

    protected function onDataPointSaved(string $key, mixed $value): void
    {
        // override this method to add custom logic
    }

    /**
     * Save extracted data to the database
     * @param ChatMessage $answer
     * @param array $data
     * @return int
     */
    public function saveExtractedData(ChatMessage $answer, array $data): int
    {
        unset($data['usage']);
        unset($data['confidence']);
        $count = 0;
        $target = $this->getModel();
        $user = auth()->user();
        foreach ($data as $key => $value) {
            // get the DataPoint by slug
            $dataPoint = DataPoint::firstWhere('slug', $key);

            // in case LLM hallucinates and sends a non-existing data point
            if ($dataPoint?->exists()) {
                // get the UserAchievement by user, achievement and target
                $achievement = UserAchievement::firstOrCreate([
                    'user_id' => $user->id,
                    'achievement_id' => $dataPoint->achievements()->firstOrFail()->id,
                    'target_type' => get_class($target),
                    'target_id' => $target->id,
                ], [
                    'progress' => 0,
                ]);

                // firstOrCreate doesn't work with json array well
                $userDataPoint = UserDataPoint::where([
                    'user_id' => $user->id,
                    'data_point_id' => $dataPoint->id,
                    'target_type' => get_class($target),
                    'target_id' => $target->id,
                    'data' => json_encode($value),
                    'user_achievement_id' => $achievement->id,
                    'is_latest' => true,
                ])->first();
                if (!$userDataPoint)
                    $userDataPoint = UserDataPoint::create([
                        'user_id' => $user->id,
                        'data_point_id' => $dataPoint->id,
                        'target_type' => get_class($target),
                        'target_id' => $target->id,
                        'data' => $value,
                        'user_achievement_id' => $achievement->id,
                        'is_latest' => true,
                    ]);

                if ($userDataPoint->wasRecentlyCreated)
                    $count++;
                $userDataPoint->chatMessages()->attach($answer->id);
                $this->onDataPointSaved($key, $value);
            }
        }
        return $count;
    }

    private function getSimilarElement(string $type, array $elementData): ?Model
    {
        if ($type === 'Writer')
            return auth()->user();
        $elements = $this->getElements($type);
        foreach ($elements as $element) {
            // todo: replace this
            if (isset($elementData['name']))
                if ($element->getComparableNameAttribute() === $elementData['name']) {
                    return $element;
                }
        }
        return null;
    }

    /**
     * @param ChatMessage $answer
     * @param array $category
     * @return Collection<ModelWithComparableNames>
     * @throws Exception
     */
    public function saveOrUpdateElements(ChatMessage $answer, array $category): Collection
    {
        // if model is new, save it first
        if ($this->getModel()->exists === false) {
            $this->model->user_id = auth()->id();
            $this->model->save();
            $this->sessionChat->target_id = $this->model->id;
            $this->sessionChat->target_type = get_class($this->model);
            $this->sessionChat->save();
//            $this->model->chats()->save($this->getSessionChat());
        }

        $otherModels = collect();
        foreach ($category as $categoryType => $categoryElements) {
            if (empty($categoryElements)) {
                continue;
            }
            foreach ($categoryElements as $elementData) {
                Log::debug('element', [$categoryType, $elementData]);
                if (isset($elementData['confidence']) && $elementData['confidence'] < 0.5) {
                    continue;
                }

                // todo: add update logic
                try {
                    if ($newModel = $this->getSimilarElement($categoryType, $elementData)) {
                        Log::debug('update', $elementData);
                    } else {
                        $newModel = $this->addElement($categoryType, $elementData, $answer);
                        $newModel?->chats()?->save($this->getChat());
                    }
                } catch (Exception $e) {
                    Log::debug($e->getMessage(), $elementData);
                    continue;
                }
                $innerElements = $elementData['categories'] ?? [];
                unset($elementData['categories']);
                // if there are inner elements,
                // we need to switch to that context and save them

                // check if we have a model
                if ($newModel) {
                    // check if we are in same context
                    if ($newModel->id === $this->model->id && get_class($newModel) === get_class($this->model)) {
                        $context = $this;
                    } else {
                        $context = self::make($newModel);
                        $otherModels->push($newModel);
                    }
                    $context->saveExtractedData($answer, $elementData);
                    if (!empty($innerElements)) {
                        // check if we are in same context
                        $otherModels->push(...$context->saveOrUpdateElements($answer, $innerElements));
                    }
                }
            }
        }
        return $otherModels;
    }

    public function getLastQuestion($includingSystem = false): ?ChatMessage
    {
        if (!$this->getModel()?->exists) return null;
        $q = $this->getSessionChat()->chatMessages()->fromAssistant();
        if (!$includingSystem) {
            $q = $q->notSystem();
        }
        return $q->latest()->first();
    }

    private function getInitialAchievement(): Achievement
    {
        return Achievement::firstWhere('slug', $this->config::INITIAL_ACHIEVEMENT_SLUG);
    }

    public function getCurrentAchievement(): ?Achievement
    {
        if (!$this->model?->exists) return null;
        $latestUa = $this->getSessionChat()->userAchievements()->latest()->first();
        if (!$latestUa) {
            Log::debug('Session dont have achievement attached, adding initial achievement');
            $latestUa = UserAchievement::create([
                'user_id' => auth()->id(),
                'achievement_id' => $this->getInitialAchievement()->id,
                'target_id' => $this->getModel()->id,
                'target_type' => get_class($this->getModel()),
                'progress' => 0,
            ]);
            $this->getSessionChat()->userAchievements()->attach($latestUa);
        }
        return $latestUa?->achievement;
    }

    private function userAchievements()
    {
        return UserAchievement::where('user_id', auth()->id())
            ->where('target_id', $this->getModel()->id)
            ->where('target_type', get_class($this->getModel()));
    }

    public function isCurrentAchievementFinished(): Achievement|bool
    {
        $target = $this->getModel();
        if (!$target)
            throw new Exception('No model to get achievements for');
        $currentAchievement = $this->getCurrentAchievement();
        if (!$currentAchievement) {
            Log::debug('No current achievement');
            return false;
        }
        return $this->userAchievements()
            ->where('target_id', $target)
            ->where('target_type', get_class($target))
            ->where('achievement_id', $currentAchievement->id)
            ->where('progress', 100)
            ->first()?->achievement
            ?? (
            (
            $currentAchievement->dataPoints()
                ->whereNotIn('slug', array_keys($this->getAskedDataPoints()))
                ->whereNotIn('data_points.id',
                    UserDataPoint::where('user_id', auth()->id())
                        ->where('target_id', $target->id)
                        ->where('target_type', get_class($target))
                        ->pluck('data_point_id')
                )
                ->count()
            ) === 0 ? $currentAchievement : false
            );
    }

    public function getNextAchievement(): ?Achievement
    {
        if ($this->config::ALLOW_ACHIEVEMENT_SWITCH === false) {
            return null;
        }
        return DataPoint::orderBy('development_order', 'asc')
            ->where('category', $this->config::ELEMENT_NAME)
            ->orderBy('impact_score', 'desc')
            ->whereNotIn(
                'achievement_id',
                $this->userAchievements()
                    ->where('progress', 100)
                    ->pluck('achievement_id')
            )
            ->first()
            ?->achievement;
    }

    public function createFirstQuestion(): ?ChatMessage
    {
        if (!empty($this->config::PREDEFINED_QUESTIONS)) {
            $firstQuestion = $this->config::PREDEFINED_QUESTIONS[array_key_first($this->config::PREDEFINED_QUESTIONS)];
            return ChatMessage::makeAiMessage(
                'text',
                $firstQuestion['question'],
                $firstQuestion['title'],
                $firstQuestion['data_points'] ?? []
            );
        }
        return null;
    }

    public function getGroupedDataPoints(array $except_topics = [], array $except_data_points = []): array
    {
        return Achievement::whereCategory($this->config::ELEMENT_NAME)
            ->whereNotIn('slug', $except_topics)
            ->with(['dataPoints'])
            ->get()
            ->map
            ->toProcessingArray($except_data_points)
            ->toArray();
    }

    public function getElementName(): string
    {
        return $this->config::ELEMENT_NAME;
    }

    public function getNextPredefinedQuestion(ChatMessage $question): ?ChatMessage
    {
        $predefinedQuestions = $this->config::PREDEFINED_QUESTIONS;
        $askedQuestions = $this->getSessionChat()->chatMessages()->notSystem()->fromAssistant()->get();
        foreach ($predefinedQuestions as $predefinedQuestion) {
            if (!$askedQuestions->contains('content', $predefinedQuestion['question'])) {
                return ChatMessage::makeAiMessage(
                    'text',
                    $predefinedQuestion['question'],
                    $predefinedQuestion['title'],
                    $predefinedQuestion['data_points'] ?? []
                );
            }
        }
        return null;
    }

    public function canSwitchEngine(): bool
    {
        return $this->config::ALLOW_ENGINE_SWITCH;
    }

    public function getIdentifier()
    {
        return $this->getModel()?->id ?? 'new';
    }

    protected abstract function getCurrentData(): array;

    protected abstract function getContextName(): string;

    protected abstract function getContextGoal(): string;

    public function getCurrentContext(string|null $question = null, string|null $answer = null): array
    {
        $c = [
            'processing_type' => 'live',
            'conversation_mode' => 'development',
            'focus' => [
                'type' => $this->getElementName(),
                'name' => $this->getContextName(),
                'current_data' => $this->getCurrentData(),
            ],
            'goal' => $this->getContextGoal(),
        ];
        if ($question) {
            $c['question_asked'] = $question;
        }
        if ($answer) {
            $c['writer_response'] = $answer;
        }
        return $c;
    }

    public function getEndpointKey(): string
    {
        return $this->config::ENDPOINT_KEY;
    }


    public function stories(): ?HasMany
    {
        return null;
    }

    public function sequences(): ?HasMany
    {
        return null;
    }

    public function themes(): ?HasMany
    {
        return null;
    }

    public function settings(): ?HasMany
    {
        return null;
    }

    public function characters(): ?HasMany
    {
        return null;
    }

    public function plots(): ?HasMany
    {
        return null;
    }
}
