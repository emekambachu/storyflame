<?php

namespace App\Jobs;

use App\Engine\Context\BaseContext;
use App\Engine\Processing\BaseProcessing;
use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class DeepElementAnalysisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @param array $otherModels
     * @param BaseProcessing $processing
     * @param ChatMessage $question
     * @param ChatMessage $answer
     */
    public function __construct(
        private readonly array          $otherModels,
        private readonly BaseProcessing $processing,
        private readonly ChatMessage    $question,
        private readonly ChatMessage    $answer
    )
    {
    }

    public function handle(): void
    {
        foreach ($this->otherModels as $model) {
            $context = BaseContext::make($model);
            $this->processing->setContext($context);
            $this->processing->setTarget($model);
            $extracted = $this->processing->extractData(
                $this->question->content,
                $this->answer->content,
                $context->getGroupedDataPoints()
            );
        }
    }
}
