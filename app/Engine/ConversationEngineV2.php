<?php

namespace App\Engine;

use App\Engine\Context\BaseContext;
use App\Engine\Processing\LocalPythonProcessing;
use App\Engine\Processing\MockProcessing;
use App\Engine\Processing\ProcessingInterface;
use App\Models\Achievement;
use App\Models\Character;
use App\Models\ChatMessage;
use App\Models\Concerns\ModelWithComparableNames;
use App\Models\Story;
use App\Models\User;
use Illuminate\Support\Collection;

final class ConversationEngineV2
{
    public BaseContext $context;
    private ProcessingInterface $processing;

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
        $this->processing = new LocalPythonProcessing();
    }

    public static function make(BaseContext|null $context = null): ConversationEngineV2
    {
        return new ConversationEngineV2($context);
    }

    public static function makeFromIdentifier(string $engine, string|null $identifier = null): ConversationEngineV2
    {
        if (!$identifier) {
            $class = match ($engine) {
                'users' => User::class,
                'stories' => Story::class,
                'characters' => Character::class,
                default => throw new \Exception('Invalid engine ' . $engine)
            };
            $model = $class::create([
                'user_id' => auth()->id(),
            ]);
        } else {
            $explode = explode('_', $identifier);
            $class = match ($explode[0]) {
                'users' => User::class,
                'stories' => Story::class,
                'characters' => Character::class,
                default => throw new \Exception('Invalid engine ' . $engine)
            };
            $model = $class::findOrFail($explode[1]);
        }

        return new ConversationEngineV2(BaseContext::make($model));
    }

    public function getIdentifier(): string
    {
        return $this->context->getIdentifier();
    }

    public function setProcessing(ProcessingInterface $processing): ConversationEngineV2
    {
        $this->processing = $processing;
        return $this;
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
            $this->context->getChat()->chatMessages()->notSystem()->oldest()->get()->toArray(),
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
     * Process basic answer
     * @param ChatMessage $question
     * @param ChatMessage $answer
     * @return ChatMessage
     */
    private function processBasicAnswer(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        $extracted = $this->processing->extractData(
            $question->content,
            $answer->content,
            $this->context->getGroupedDataPoints()
        );
        dump($extracted);
        if (!isset($extracted['elements'])) {
            dd('implement no elements');
        }
        $otherElements = $this->context->saveOrUpdateElements($answer, $extracted['elements']);

        // if user mentioned some other elements
        // and config allows to switch engine
        if ($this->context->canSwitchEngine() && count($otherElements)) {
            // confirm if the user wants to switch to that context
            if ($otherElements->count() === 1) {
                $this->context = BaseContext::make($otherElements->first());
            } else {
                return $this->processing->generateContextSwitchQuestion(
                    $otherElements->map(function (ModelWithComparableNames $element) {
                        return [
                            'name' => $element->getComparableNameAttribute(),
                            'id' => $element->id,
                            'type' => get_class($element)
                        ];
                    }),
                    $this->context->getChat()->chatMessages()->notSystem()->oldest()->get()->toArray(),
                );
            }
        } else {
            // if we have predefined question for the next question
            if ($predefined = $this->context->getNextPredefinedQuestion($question)) {
                dd('predefined question');
                return $predefined;
            }

            // check if the current achievement is finished
            if ($achievement = $this->context->isCurrentAchievementFinished()) {
                dd('achievement finished');
                // switch to the next achievement
                return $this->switchAchievement();
            }
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
        if ($question->expectsConfirmation()) {
            dd('implement confirmation');
        }
        return $this->processBasicAnswer($question, $answer);
    }

    private function processConversation(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        dump($question->content, $answer->content);

        $rating = $this->processing->rateResponse($question->content, $answer->content);
        $answer->extra_attributes->set('rating', $rating);
        $answer->save();

        dump($rating);

        if (isset($rating['is_skipped']) && $rating['is_skipped']) {
            dd('implement skip');
        } else if (isset($rating['we_dont_understand']) && $rating['we_dont_understand']) {
            dd('implement we dont understand');
        } else if (isset($rating['user_not_understand']) && $rating['user_not_understand']) {
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
        } else if (isset($rating['user_dont_know']) && $rating['user_dont_know']) {
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
}
