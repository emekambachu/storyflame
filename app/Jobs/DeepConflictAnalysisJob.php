<?php

namespace App\Jobs;

use App\Models\DataPoint;
use App\Services\ProcessingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeepConflictAnalysisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $dataPointSlug,
        private readonly mixed  $oldValue,
        private readonly mixed  $newValue
    )
    {
    }

    public function handle(): void
    {
        // Here we can analyze the conflict between the old and new values
        // and decide what to do with it

        $dataPoint = DataPoint::firstWhere('slug', $this->dataPointSlug);

        ProcessingService::analyzeConflict(
            $dataPoint,
            $this->oldValue,
            $this->newValue
        );
    }
}
