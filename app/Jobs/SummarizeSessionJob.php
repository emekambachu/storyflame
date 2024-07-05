<?php

namespace App\Jobs;

use App\Models\Character;
use App\Models\Chat\SessionChat;
use App\Models\Story;
use App\Models\Summary\Summary;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserSummary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SummarizeSessionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly SessionChat $sessionChat
    )
    {
    }

    public function handle(): void
    {
        /** @var Story $target */
        Log::info('Summarizing session', ['session_chat_id' => $this->sessionChat->id]);
        $createdSummaries = 0;
        $updatedSummaries = 0;
        $target = $this->sessionChat->target;
//        foreach (UserAchievement::where('target_type', get_class($target))
//                     ->where('target_id', $target->id)
//                     ->where('progress', '>=', 1)->get() as $achievement) {
//            foreach (Summary::whereCategory('Achievement')->get() as $summary) {
//                $userSummary = UserSummary::firstOrCreate([
//                    'user_id' => $this->sessionChat->chat->sender_id,
//                    'summary_id' => $summary->id,
//                    'target_id' => $achievement->id,
//                    'target_type' => get_class($achievement),
//                ]);
//                GenerateAchievementUserSummaryJob::dispatch($userSummary);
//                if ($userSummary->wasRecentlyCreated) {
//                    $createdSummaries++;
//                } else {
//                    $updatedSummaries++;
//                }
//            }
//        }

        $summaryCategory = match (get_class($target)) {
            User::class => 'User',
            Story::class => 'Story (aka episode, novel)',
            Character::class => 'Character',
            default => null,
        };


        foreach (Summary::whereCategory($summaryCategory)->get() as $summary) {
            $valid = true;
            $dataPoints = $target->dataPoints;
            if ($dataPoints->count() === 0) {
                $valid = false;
            }

            foreach ($summary->schemas as $schema) {
                if ($schema->is_required) {
                    if ($dataPoints->find('item_id', $schema->schemaable_id) === null) {
                        $valid = false;
                        Log::info('Summary not valid, missing required element ' . $schema->schemaable_id);
                        break;
                    }
                }
            }

            if ($valid) {
                $userSummary = UserSummary::firstOrCreate([
                    'user_id' => $this->sessionChat->chat->sender_id,
                    'summary_id' => $summary->id,
                    'target_id' => $target->id,
                    'target_type' => get_class($target),
                ]);
                GenerateUserSummaryJob::dispatch($userSummary);
                if ($userSummary->wasRecentlyCreated) {
                    $createdSummaries++;
                } else {
                    $updatedSummaries++;
                }
            }
        }

        Log::info('Summarized session', [
            'session_chat_id' => $this->sessionChat->id,
            'created_summaries' => $createdSummaries,
            'updated_summaries' => $updatedSummaries,
        ]);
    }
}
