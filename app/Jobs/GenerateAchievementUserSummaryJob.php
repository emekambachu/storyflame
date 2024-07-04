<?php

namespace App\Jobs;

use App\Models\UserSummary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateAchievementUserSummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly UserSummary $userSummary)
    {
    }

    public function handle(): void
    {
        Log::debug('Generating achievement user summary', ['user_summary_id' => $this->userSummary->id]);
        $this->userSummary->update([
            'summary' => $this->userSummary->summarySchema->slug . ' for ' . $this->userSummary->target->id,
        ]);
    }
}
