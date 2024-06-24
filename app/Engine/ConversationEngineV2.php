<?php

namespace App\Engine;

use App\Engine\Config\OnboardingEngineConfig;
use App\Engine\Context\BaseContext;
use App\Engine\Processing\BaseProcessing;
use App\Engine\Processing\LocalPythonProcessing;
use App\Engine\Processing\MockProcessing;
use App\Jobs\DeepElementAnalysisJob;
use App\Models\Achievement;
use App\Models\Character;
use App\Models\ChatMessage;
use App\Models\Concerns\ModelWithComparableNames;
use App\Models\Story;
use App\Models\User;
use App\Models\UserDataPoint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final class ConversationEngineV2
{
    private BaseContext $context;
    private BaseProcessing $processing;

    public function __construct(
        BaseContext|null $context = null
    )
    {
        // if context is not provided,
        // meaning that the user started a new conversation
        // we'll use the current user as the context
        if (!$context) {
            // initialize new user context
            $this->context = BaseContext::make(auth()->user());
        } else {
            $this->context = $context;
        }

        // set default processing engine
        $this->setProcessing(new LocalPythonProcessing($context));
    }

    public static function make(BaseContext|null $context = null): ConversationEngineV2
    {
        return new ConversationEngineV2($context);
    }

    private function setContext(BaseContext $context): ConversationEngineV2
    {
        $this->context = $context;
        $this->processing->setTarget($context->getModel());
        $this->processing->setContext($context);
        return $this;
    }

    public function setProcessing(BaseProcessing $processing): ConversationEngineV2
    {
        $this->processing = $processing;
        $this->processing->setTarget($this->context->getModel() ?? null);
        $this->processing->setContext($this->context);
        return $this;
    }

    public static function makeFromIdentifier(string $engine, string|null $identifier = null): ConversationEngineV2
    {
        // get the class based on the engine
        $class = match ($engine) {
            'users', 'onboarding' => User::class,
            'stories' => Story::class,
            'characters' => Character::class,
            default => throw new \Exception('Invalid engine ' . $engine)
        };

        // if the engine is user, we'll use the current user as the context
        if ($class === User::class) {
            $model = auth()->user();
        } else {
            // if the identifier is provided and its not 'new' then we'll find the model
            if ($identifier && $identifier !== 'new') {
                $model = $class::findOrFail($identifier);
            } else {
                $model = new $class();
            }
        }

        $context = BaseContext::make($model);

        if ($engine === 'onboarding') {
            $context->withConfig(new OnboardingEngineConfig());
        }

        return new ConversationEngineV2($context);
    }

    public function getIdentifier(): string
    {
        return $this->context->getIdentifier();
    }

    private function createFirstQuestion(): ChatMessage
    {
        $firstQuestion = $this->context->createFirstQuestion();
        if (!$firstQuestion) {
            dd('Implement first question generation');
        }
        return $firstQuestion;
    }

    private function generateNextQuestion(string $type, Collection $achievements): ChatMessage
    {
        $nextQuestion = $this->processing->generateNextQuestion(
            $this->context->getModel(),
            $this->context->getChat()->chatMessages()->notSystem()->oldest()->get()->map->toProcessingArray()->toArray(),
            $achievements
                ->map
                ->toProcessingArray()
                ->toArray(),
            $type
        );
        return $this->context->getChat()->chatMessages()->create([
            'content' => $nextQuestion['question'],
            'type' => 'text',
            'extra_attributes' => [
                'title' => $nextQuestion['title'],
                'data_points' => $nextQuestion['data_points'] ?? [],
                'examples' => $nextQuestion['examples'] ?? [],
                'tooltip' => $nextQuestion['tooltip'] ?? '',
            ]
        ]);
    }

    private function switchAchievement(): ChatMessage
    {
        $nextAchievement = $this->context->getNextAchievement();
        if (!$nextAchievement) {
            return $this->context->getChat()->chatMessages()->create([
                'type' => 'system',
                'content' => 'finish'
            ]);
        }
        $nextQuestion = $this->generateNextQuestion(
            'switch',
            collect([$nextAchievement])
        );
        $nextQuestion->achievement()->attach($nextAchievement);
        $nextQuestion->save();
        return $nextQuestion;
    }

    /**
     * @param Collection<ModelWithComparableNames> $elements
     * @return ChatMessage
     */
    private function switchContext(Collection $elements): ChatMessage
    {
        $selectElements = $elements->map(function (ModelWithComparableNames $element) {
            return [
                'name' => $element->getComparableNameAttribute(),
                'id' => $element->id,
                'type' => get_class($element)
            ];
        })->toArray();
        $q = $this->processing->generateContextSwitchQuestion(
            $selectElements,
            $this->context->getChat()->chatMessages()->notSystem()->oldest()->get()->toArray(),
        );
        return $this->context->getChat()->chatMessages()->create([
            'content' => $q['question'],
            'type' => 'confirm_switch_context',
            'extra_attributes' => [
                'title' => $q['title'],
                'data_points' => $q['data_points'] ?? [],
                'select_elements' => $selectElements
            ]
        ]);
    }

    /**
     * Process basic answer
     * @param ChatMessage $question
     * @param ChatMessage $answer
     * @return ChatMessage
     */
    private function processBasicAnswer(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        $extractCategories = $this->processing->extractCategories(
            $question->content,
            $answer->content,
        );
        Log::debug('extracted', $extractCategories);
        if (!isset($extractCategories['categories'])) {
            dd('implement no elements');
        }
        $otherElements = $this->context->saveOrUpdateElements($answer, $extractCategories['categories']);
        Log::debug('other elements ct: ' . $otherElements->count());

        $extractedData = $this->processing->extractData(
            $question->content,
            $answer->content,
            $this->context->getGroupedDataPoints()
        );

        $this->context->saveExtractedData(
            $answer,
            collect($extractedData)
                ->flatMap(fn($data) => $data['data_points'])
                ->filter(fn($item) => isset($item['data_point_id']) && isset($item['data_point_value']))
                ->mapWithKeys(fn($item) => [$item['data_point_id'] => $item['data_point_value']])
                ->toArray()
        );

        // if we have other categories extracted
        // we'll extract all the data for them
        // in background
        if (count($otherElements)) {
            DeepElementAnalysisJob::dispatch(
                $otherElements->toArray(),
                $this->processing,
                $question,
                $answer
            );
        }

//        // if user mentioned some other elements
//        // and config allows to switch engine
//        if ($this->context->canSwitchEngine() && count($otherElements)) {
//            // confirm if the user wants to switch to that context
//            // todo: remove this, it's for testing
//            if ($otherElements->count() === 1) {
//                $this->setContext(BaseContext::make($otherElements->first()));
//            } else {
//                Log::debug('switch context');
//                return $this->switchContext($otherElements);
//            }
//        }

        // if we have predefined question for the next question
        if ($predefined = $this->context->getNextPredefinedQuestion($question)) {
            Log::debug('predefined question', [$predefined->content]);
            return $predefined;
        }

        // check if the current achievement is finished
        if ($achievement = $this->context->isCurrentAchievementFinished()) {
            dd('achievement finished');
            // switch to the next achievement
            return $this->switchAchievement();
        }
        return $this->generateNextQuestion('basic', collect([$this->context->getCurrentAchievement()]));
    }

    /**
     * Process good meaningful answer
     * @param ChatMessage $question
     * @param ChatMessage $answer
     * @return ChatMessage
     */
    private function processDefaultAnswer(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        // if message of type confirm_* then process it
        if ($question->expectsConfirmation()) {
            $result = $this->processing->isPositiveConfirmation(
                $question->content,
                $answer->content,
                $question->extra_attributes->get('select_elements', null)
            );
            if ($result['is_positive']) {
                if (isset($result['is_selected']) && $result['is_selected']) {
                    $type = $result['selected_type'];
                    $id = $result['selected_id'];
                    $this->setContext(BaseContext::make($type::findOrFail($id)));
                    return $this->generateNextQuestion(
                        'basic',
                        collect([$this->context->getCurrentAchievement()])
                    );
                } else {
                    dd('ask user to select');
                }
            } else {
                return $this->generateNextQuestion(
                    'basic',
                    collect([$this->context->getCurrentAchievement()])
                );
            }
        }
        return $this->processBasicAnswer($question, $answer);
    }

    private function processConversation(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        Log::debug('qa', [$question->content, $answer->content]);

        $rating = $this->processing->rateResponse($question->content, $answer->content);
        $answer->extra_attributes->set('rating', $rating);
        $answer->save();

        Log::debug('rating', $rating);

        if (isset($rating['is_skipped']) && $rating['is_skipped'] > 0.7) {
            dd('implement skip');
        } else if (isset($rating['we_do_not_understand']) && $rating['we_do_not_understand'] > 0.7) {
            dd('implement we dont understand');
        } else if (isset($rating['user_does_not_understand']) && $rating['user_does_not_understand'] > 0.7) {
            $previousDataPoints = Achievement::whereHas('dataPoints', function ($query) use ($question) {
                $query->whereIn('slug', $question->extra_attributes->get('data_points', []));
            })->get();
            return $this->generateNextQuestion(
                'follow-up',
                $previousDataPoints->load([
                    'dataPoints' => function ($query) use ($question) {
                        $query->whereIn('slug', $question->extra_attributes->get('data_points', []));
                    }
                ])
            );
        } else if (isset($rating['user_does_not_know']) && $rating['user_does_not_know'] > 0.7) {
            $previousDataPoints = Achievement::whereHas('dataPoints', function ($query) use ($question) {
                $query->whereIn('slug', $question->extra_attributes->get('data_points', []));
            })->get();
            return $this->generateNextQuestion(
                'brainstorm',
                $previousDataPoints->load([
                    'dataPoints' => function ($query) use ($question) {
                        $query->whereIn('slug', $question->extra_attributes->get('data_points', []));
                    }
                ])
            );
        }
        return $this->processDefaultAnswer($question, $answer);
    }

    public function process(string $message): ChatMessage
    {
        $lastQuestion = $this->context->getLastQuestion();
        Log::debug('last question', [$this->context->getIdentifier(), $lastQuestion->content]);
        if (!$lastQuestion) {
            $lastQuestion = $this->createFirstQuestion();
            if ($this->processing instanceof MockProcessing) {
                $this->processing->simulateDelay();
            }
        }
        $answer = $this->context->getChat()->chatMessages()->create([
            'content' => $message,
            'user_id' => auth()->id(),
            'type' => 'text',
        ]);
        if ($this->processing instanceof MockProcessing) {
            $this->processing->simulateDelay();
        }

        $nextQuestion = $this->processConversation($lastQuestion, $answer);
        $this->context->getChat()->chatMessages()->save(
            $nextQuestion
        );

        return $nextQuestion;
    }

    public function getLastQuestion(): ChatMessage
    {
        return $this->context->getLastQuestion() ?? $this->createFirstQuestion();
    }

    public function getEndpoint()
    {
        return $this->context->getEndpointKey();
    }

    public function getProgress()
    {
        $achievement = $this->context->getCurrentAchievement();
        $model = $this->context->getModel();
        if (!$model || !$achievement || !$model->id) return 0;
        $data_points_count = $achievement->dataPoints->count();
        $user_data_points_count = UserDataPoint::where('user_id', auth()->id())
            ->whereIn('data_point_id', $achievement->dataPoints->pluck('id'))
            ->where('is_latest', true)
            ->where('target_id', $model->id)
            ->where('target_type', get_class($model))
            ->count();
        return $data_points_count > 0 ? ($user_data_points_count / $data_points_count) * 100 : 0;
    }
}
