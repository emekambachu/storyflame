<?php

namespace App\Jobs;

use App\Engine\ConversationEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeepElementAnalysisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param ConversationEngine $engine engine to use
     * @param array $extracted extracted data to exclude from deep extraction
     */
    public function __construct(
        private readonly string             $question,
        private readonly string             $answer,
        private readonly ConversationEngine $engine,
        private readonly array              $extracted
    )
    {
    }

    public function handle(): void
    {
        return;
        $all_data = $this->engine->getStorage()->getExtractedData();

        // Try to extract everything from response without data that was already extracted
        $extracted = $this->engine->extractEverything(
            $this->question,
            $this->answer,
            array_keys($this->extracted)
        );

        foreach ($extracted as $key => $value) {
            if (isset($all_data[$key])) {
                DeepConflictAnalysisJob::dispatch($key, $all_data[$key], $value);
            }
        }
    }
}
