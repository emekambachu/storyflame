<?php

namespace App\Engine;

use App\Engine\Context\BaseContext;
use App\Engine\Processing\BaseProcessing;
use App\Models\ChatMessage;
use App\Models\Achievement;
use App\Models\Concerns\ModelWithComparableNames;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class QuestionGenerator
{
    private BaseContext $context;
    private BaseProcessing $processing;

    public function __construct(BaseContext $context, BaseProcessing $processing)
    {
        $this->context = $context;
        $this->processing = $processing;
    }

    public static function make(BaseContext $context, BaseProcessing $processing): self
    {
        return new self($context, $processing);
    }

    public function createFirstQuestion(): ChatMessage
    {
        $firstQuestion = $this->context->createFirstQuestion();
        $firstQuestion->content = $this->context->replaceTemplate($firstQuestion->content);
        $firstQuestion->extra_attributes->put('title', $this->context->replaceTemplate($firstQuestion->extra_attributes->get('title', '')));
        if (!$firstQuestion) {
            Log::debug('First question did not exist for', [$this->context->getIdentifier()]);
            dd('Implement first question generation');
        }
        return $firstQuestion;
    }

    public function generateNextQuestion(string $type, Collection $achievements): ChatMessage
    {
        Log::info('excluding', $this->context->getAskedDataPoints() ?? []);
        $dataPoints = $achievements
            ->map
            ->toProcessingArray(array_keys($this->context->getAskedDataPoints()))
            ->toArray();
        Log::info('generate next question', [
            $type,
            $dataPoints
        ]);
        $nextQuestion = $this->processing->generateNextQuestion(
            $this->context->getModel(),
            $this->context->getHistory(),
            $dataPoints,
            $type
        );
        Log::debug('next question', [$nextQuestion]);
        return ChatMessage::makeAiMessage(
            'text',
            $nextQuestion['question'],
            $nextQuestion['title'],
            $nextQuestion['data_points'] ?? [],
            [
                'examples' => $nextQuestion['examples'] ?? [],
                'tooltip' => $nextQuestion['tooltip'] ?? ''
            ]
        );
    }

    public function switchAchievement(): ChatMessage
    {
        $nextAchievement = $this->context->getNextAchievement();
        Log::debug('next achievement', [$nextAchievement]);
        if (!$nextAchievement) {
            return ChatMessage::makeSystemMessage('finish');
        }
        $nextQuestion = $this->generateNextQuestion(
            'switch',
            collect([$nextAchievement])
        );
        $nextQuestion->achievement()->attach($nextAchievement);
        $nextQuestion->save();
        return $nextQuestion;
    }

    public function switchContext(Collection $elements): ChatMessage
    {
        $selectElements = $elements->map(function (ModelWithComparableNames $element) {
            return [
                'name' => $element->getComparableNameAttribute(),
                'id' => $element->id,
                'type' => get_class($element)
            ];
        })->toArray();
        $question = $this->processing->generateContextSwitchQuestion(
            $selectElements,
            $this->context->getHistory(),
        );
        return ChatMessage::makeAiMessage(
            'confirm_switch_context',
            $question['question'],
            $question['title'],
            $question['data_points'],
            [
                'select_elements' => $selectElements
            ]
        );
    }
}
