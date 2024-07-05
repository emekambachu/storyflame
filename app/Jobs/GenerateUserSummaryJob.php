<?php

namespace App\Jobs;

use App\Engine\Processing\LocalPythonProcessing;
use App\Models\UserDataPoint;
use App\Models\UserSummary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateUserSummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly UserSummary $userSummary
    )
    {
    }

    public function handle(): void
    {
        $processing = new LocalPythonProcessing(
            null,
            $this->userSummary->target
        );


        $this->userSummary->update([
//            'summary' => $this->userSummary->summarySchema->slug . ' for ' . $this->userSummary->target->id,
            'summary' => trim($processing->generateSummary(
                $this->userSummary->summarySchema->name,
                $this->userSummary->summarySchema->length,
                $this->userSummary->summarySchema->purpose,
                $this->userSummary->summarySchema->example_summary,
                $this->userSummary->target->dataPoints
                    ->mapWithKeys(
                        fn(UserDataPoint $dp) => [$dp->dataPoint->name => $dp->data]
                    )->toArray()
            ))
        ]);
    }
}
