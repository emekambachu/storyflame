<?php

namespace App\Engine;

use App\Engine\Config\OnboardingEngineConfig;
use App\Engine\Context\BaseContext;
use App\Engine\Processing\BaseProcessing;
use App\Engine\Processing\LocalPythonProcessing;
use App\Engine\Processing\MockProcessing;
use App\Models\Achievement;
use App\Models\ChatMessage;
use App\Models\Story;
use App\Models\StoryElements\Character;
use App\Models\User;
use Illuminate\Support\Facades\Log;

final class ConversationEngineV2
{
    private BaseContext $context;
    private BaseProcessing $processing;
    private AnswerProcessor $answerProcessor;
    private QuestionGenerator $questionGenerator;

    public function __construct(BaseContext|null $context = null)
    {
        $this->context = $context ?? BaseContext::make(auth()->user());
        $this->setProcessing(new LocalPythonProcessing($context));
        $this->answerProcessor = new AnswerProcessor($this->context, $this->processing);
        $this->questionGenerator = new QuestionGenerator($this->context, $this->processing);
    }

    public static function make(BaseContext|null $context = null): self
    {
        return new self($context);
    }

    public function getModel()
    {
        return $this->context->getModel() ?? null;
    }

    private function setContext(BaseContext $context): self
    {
        $this->context = $context;
        $this->processing->setTarget($context->getModel());
        $this->processing->setContext($context);
        $this->answerProcessor = new AnswerProcessor($this->context, $this->processing);
        $this->questionGenerator = new QuestionGenerator($this->context, $this->processing);
        return $this;
    }

    public function setProcessing(BaseProcessing $processing): self
    {
        $this->processing = $processing;
        $this->processing->setTarget($this->context->getModel() ?? null);
        $this->processing->setContext($this->context);
        $this->answerProcessor = new AnswerProcessor($this->context, $this->processing);
        $this->questionGenerator = new QuestionGenerator($this->context, $this->processing);
        return $this;
    }

    public static function makeFromIdentifier(string $engine, string|null $identifier = null): self
    {
        $class = match ($engine) {
            'users', 'onboarding' => User::class,
            'stories' => Story::class,
            'characters' => Character::class,
            default => throw new \Exception('Invalid engine ' . $engine)
        };

        $model = $class === User::class ? auth()->user() : ($identifier && $identifier !== 'new' ? $class::findOrFail($identifier) : new $class());

        $context = BaseContext::make($model);

        if ($engine === 'onboarding') {
            $context->withConfig(new OnboardingEngineConfig());
        }

        return new self($context);
    }

    public function getIdentifier(): string
    {
        return $this->context->getIdentifier();
    }

    public function finish()
    {
        $this->context->saveMessage(ChatMessage::makeSystemMessage('finish'));
    }

    public function process(string $message): ChatMessage
    {
        $question = $this->context->getPreviousQuestion() ?? $this->questionGenerator->createFirstQuestion();
        $this->context->saveMessage($question);
        $this->simulateDelayForMockProcessing();

        $answer = $this->context->saveMessage(ChatMessage::makeUserMessage($message));
        $this->simulateDelayForMockProcessing();

        return $this->context->saveMessage(
            $this->answerProcessor->processAnswer($question, $answer)
        );
    }

    private function simulateDelayForMockProcessing()
    {
        if ($this->processing instanceof MockProcessing) {
            $this->processing->simulateDelay();
        }
    }

    public function getPreviousQuestion($includingSystem = false): ChatMessage
    {
        return $this->context->getPreviousQuestion($includingSystem) ?? $this->questionGenerator->createFirstQuestion();
    }

    public function getEndpoint()
    {
        return $this->context->getEndpointKey();
    }

    public function getProgress()
    {
        return $this->context->getProgress();
    }
}
