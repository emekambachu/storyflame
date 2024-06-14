<?php

namespace App\Engine\Context;

use App\Engine\Config\EngineConfig;
use App\Engine\Config\StoryEngineConfig;
use App\Models\Achievement;
use App\Models\Character;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Concerns\ModelWithComparableNames;
use App\Models\Concerns\ModelWIthRelatedChats;
use App\Models\DataPoint;
use App\Models\Story;
use App\Models\User;
use App\Models\UserAchievement;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @template T of ModelWIthRelatedChats
 */
abstract class BaseContext implements ContextInterface
{
    protected EngineConfig $config;

    /**
     * @param T $model
     */
    public function __construct(
        protected $model = null
    )
    {
    }

    public function getChat(): Chat
    {
        // TODO: change this to latest session chat
        if ($this->getModel()->chats()->count() === 0) {
            $chat = Chat::create([
                'type' => 'conversation',
                'sender_id' => auth()->id(),
            ]);
            $this->getModel()->chats()->save($chat);
        }
        return $this->getModel()->chats()->first();
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
            Story::class => new StoryContext(),
            User::class => new UserContext(),
            Character::class => new CharacterContext(),
            default => throw new Exception('Unknown model type ' . $model::class)
        })->setModel($model);
    }

    public function withConfig(EngineConfig $config): static
    {
        $this->config = $config;
        return $this;
    }

    public function getElements(string $elementType): array
    {
        return match ($elementType) {
            'Character' => $this->getCharacters(),
            default => []
        };
    }

    public function addElement(string $elementType, array $elementData): ?ModelWithComparableNames
    {
        return match ($elementType) {
            'Story' => $this->stories()->create($elementData),
            'Character' => $this->characters()->create($elementData),
            'Plot' => $this->plots()->create($elementData),
            'Sequence' => $this->sequences()->create($elementData),
            default => null
        };
    }

    protected function onDataPointSaved(string $key, mixed $value): void
    {
        // override this method to add custom logic
    }

    public function saveExtractedData(ChatMessage $answer, array $data): void
    {
        $target = $this->getModel();
        $user = auth()->user();
        foreach ($data as $key => $value) {
            $dataPoint = DataPoint::firstWhere('slug', $key);
            if ($dataPoint->exists()) {
                $achievement = UserAchievement::firstOrCreate([
                    'user_id' => $user->id,
                    'achievement_id' => $dataPoint->achievement_id,
                    'target_type' => get_class($target),
                    'target_id' => $target->id,
                ], [
                    'progress' => 0,
                ]);
                $dataPoint = $user->dataPoints()->create([
                    'data_point_id' => $dataPoint->id,
                    'target_type' => get_class($target),
                    'target_id' => $target->id,
                    'data' => $value,
                    'user_achievement_id' => $achievement->id,
                ]);
                $dataPoint->chatMessages()->attach($answer->id);
                $this->onDataPointSaved($key, $value);
            }
        }
    }

    private function getSimilarElement(string $type, array $elementData): ?Model
    {
        if ($type === 'Writer')
            return auth()->user();
        $elements = $this->getElements($type);
        foreach ($elements as $element) {
            // todo: replace this
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
        $otherModels = collect();
        foreach ($category as $categoryType => $categoryElements) {
            foreach ($categoryElements as $elementData) {
                // todo: add update logic
                if ($newModel = $this->getSimilarElement($categoryType, $elementData)) {

                } else
                    $newModel = $this->addElement($categoryType, $elementData, $answer);
                $innerElements = $elementData['elements'] ?? [];
                unset($elementData['elements']);
                // if there are inner elements,
                // we need to switch to that context and save them
                if ($newModel) {
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

    public function getLastQuestion(): ?ChatMessage
    {
        return $this->getChat()->chatMessages()->notSystem()->fromAssistant()->latest()->first();
    }

    private function getInitialAchievement(): Achievement
    {
        return Achievement::firstWhere('slug', $this->config::INITIAL_ACHIEVEMENT_SLUG);
    }

    public function getCurrentAchievement(): Achievement
    {
        $lastQuestion = $this->getChat()->chatMessages()->notSystem()->fromAssistant()->whereHas('achievement')->latest()->first();
        if ($lastQuestion) {
            return $lastQuestion->achievement->first();
        }
        return $this->getInitialAchievement();
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
        return $this->userAchievements()
            ->where('achievement_id', $this->getCurrentAchievement()->id)
            ->where('progress', 100)
            ->first()?->achievement ?? false;
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
            $chatMessage = $this->getChat()->chatMessages()->create([
                'type' => 'text',
                'content' => $firstQuestion['question'],
                'extra_attributes' => [
                    'title' => $firstQuestion['title'],
                    'data_points' => $firstQuestion['data_points'] ?? [],
                ]
            ]);
            $chatMessage->achievement()->attach($this->getInitialAchievement());
            $chatMessage->save();
            return $chatMessage;
        }
        return null;
    }

    public function getGroupedDataPoints(array $except_topics = [], array $except_data_points = []): array
    {
        return Achievement::where('element', $this->config::ELEMENT_NAME)
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
        $askedQuestions = $this->getChat()->chatMessages()->notSystem()->fromAssistant()->get();
        foreach ($predefinedQuestions as $predefinedQuestion) {
            if (!$askedQuestions->contains('content', $predefinedQuestion['question'])) {
                return $this->getChat()->chatMessages()->create([
                    'type' => 'text',
                    'content' => $predefinedQuestion['question'],
                    'extra_attributes' => [
                        'title' => $predefinedQuestion['title'],
                        'data_points' => $predefinedQuestion['data_points'] ?? [],
                    ]
                ]);
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
        return match (get_class($this->config)) {
            StoryEngineConfig::class => 'stories',
        } . '_' . $this->getModel()->id;
    }
}
