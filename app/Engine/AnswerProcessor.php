<?php

namespace App\Engine;

use App\Engine\Context\BaseContext;
use App\Engine\Processing\BaseProcessing;
use App\Models\ChatMessage;
use App\Models\Achievement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AnswerProcessor
{
    private BaseContext $context;
    private BaseProcessing $processing;

    public function __construct(BaseContext $context, BaseProcessing $processing)
    {
        $this->context = $context;
        $this->processing = $processing;
    }

    public function processAnswer(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        Log::debug('qa', [
            $question->content,
            $answer->content
        ]);

        $rating = $this->processing->rateResponse($question->content, $answer->content);
        $answer->extra_attributes->set('rating', $rating);
        $answer->save();

        return $this->determineNextAction($rating, $question, $answer);
    }

    private function determineNextAction(array $rating, ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        Log::debug('rating', $rating);

        if ($this->isSkipped($rating)) {
            return QuestionGenerator::make($this->context, $this->processing)->generateNextQuestion(
                'basic',
                collect([$this->context->getCurrentAchievement()])
            );
        }

        if ($this->weDoNotUnderstand($rating)) {
            return QuestionGenerator::make($this->context, $this->processing)->generateNextQuestion(
                'basic',
                collect([$this->context->getCurrentAchievement()])
            );
        }

        if ($this->userDoesNotUnderstand($rating)) {
            $previousDataPoints = $this->getPreviousDataPoints($question);
            return QuestionGenerator::make($this->context, $this->processing)->generateNextQuestion(
                'brainstorm',
                $previousDataPoints
            );
        }

        if ($this->userDoesNotKnow($rating)) {
            $previousDataPoints = $this->getPreviousDataPoints($question);
            return QuestionGenerator::make($this->context, $this->processing)->generateNextQuestion(
                'brainstorm',
                $previousDataPoints
            );
        }

        return $this->processDefaultAnswer($question, $answer);
    }

    // TODO: We need an endpoint and controller->method and processing here for if the user presses a "Change Topic" button, and expressly tells us what they want to change the topic to

    // TODO: create a new method for doesWriterWantToChangeTopic
    private function doesWriterWantToChangeTopic(array $rating): bool
    {
        // TODO: need to create this rating in Python
        // TODO: Send back a system message to the user asking if they want to change the topic
        return isset($rating['does_writer_want_to_change_topic']) && $rating['does_writer_want_to_change_topic'] > 0.7;
    }

    private function isSkipped(array $rating): bool
    {
        return isset($rating['is_skipped']) && $rating['is_skipped'] > 0.7;
    }

    private function weDoNotUnderstand(array $rating): bool
    {
        return isset($rating['we_do_not_understand']) && $rating['we_do_not_understand'] > 0.7;
    }

    private function userDoesNotUnderstand(array $rating): bool
    {
        return isset($rating['user_does_not_understand']) && $rating['user_does_not_understand'] > 0.7;
    }

    private function userDoesNotKnow(array $rating): bool
    {
        return isset($rating['user_does_not_know']) && $rating['user_does_not_know'] > 0.7;
    }

    private function getPreviousDataPoints(ChatMessage $question): Collection
    {
        return Achievement::whereHas('dataPoints', function ($query) use ($question) {
            $query->whereIn('slug', $question->extra_attributes->get('data_points', []));
        })->get();
    }

    private function processDefaultAnswer(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        if ($question->expectsConfirmation()) {
            return $this->handleConfirmation($question, $answer);
        }
        return $this->processBasicAnswer($question, $answer);
    }

    private function handleConfirmation(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        $result = $this->processing->isPositiveConfirmation(
            $question->content,
            $answer->content,
            $question->extra_attributes->get('select_elements', null)
        );

        if ($result['is_positive']) {
            if (isset($result['is_selected']) && $result['is_selected']) {
                $type = $result['selected_type'];
                $id = $result['selected_id'];
                $this->context->setContext(BaseContext::make($type::findOrFail($id)));
                return QuestionGenerator::make($this->context, $this->processing)->generateNextQuestion(
                    'basic',
                    collect([$this->context->getCurrentAchievement()])
                );
            } else {
                throw new \Exception('Ask user to select');
            }
        } else {
            return QuestionGenerator::make($this->context, $this->processing)->generateNextQuestion(
                'basic',
                collect([$this->context->getCurrentAchievement()])
            );
        }
    }

    private function processBasicAnswer(ChatMessage $question, ChatMessage $answer): ChatMessage
    {
        $extractCategories = $this->processing->extractCategories(
            $question->content,
            $answer->content,
        );
        Log::debug('extracted categories', $extractCategories);

        $otherElements = $this->context->saveOrUpdateElements($answer, $extractCategories['categories']);
        Log::debug('other elements count: ' . $otherElements->count());

        $extractedData = $this->processing->extractData(
            $question->content,
            $answer->content,
            $this->context->getGroupedDataPoints()
        );

        Log::debug('extracted data', $extractedData);

        $savedCount = $this->context->saveExtractedData(
            $answer,
            collect($extractedData)
                ->flatMap(fn($data) => $data['data_points'])
                ->filter(fn($item) => isset($item['data_point_id']) && isset($item['data_point_value']))
                ->mapWithKeys(fn($item) => [$item['data_point_id'] => $item['data_point_value']])
                ->toArray()
        );

        if ($savedCount === 0) {
//            $sessionChat = $this->context->();
        }

        if (!empty($otherElements)) {
//            DeepElementAnalysisJob::dispatch(
//                $otherElements->toArray(),
//                $this->processing,
//                $question,
//                $answer
//            );
        }

        if ($this->context->canSwitchEngine() && $otherElements->isNotEmpty()) {
            return $this->handleContextSwitch($otherElements);
        }

        if ($predefined = $this->context->getNextPredefinedQuestion($question)) {
            Log::debug('predefined question', [$predefined->content]);
            return $predefined;
        }

        if ($achievement = $this->context->isCurrentAchievementFinished()) {
            return QuestionGenerator::make($this->context, $this->processing)->switchAchievement();
        }

        return QuestionGenerator::make($this->context, $this->processing)->generateNextQuestion(
            'basic',
            collect([$this->context->getCurrentAchievement()])
        );
    }

    private function handleContextSwitch(Collection $otherElements): ?ChatMessage
    {
        if ($otherElements->count() === 1) {
            $this->context->setContext(BaseContext::make($otherElements->first()));
        } else {
            Log::debug('switch context to', $otherElements->pluck('id')->toArray());
            return QuestionGenerator::make($this->context, $this->processing)->switchContext($otherElements);
        }
        return null;
    }
}
