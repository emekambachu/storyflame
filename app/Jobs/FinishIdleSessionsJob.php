<?php

namespace App\Jobs;

use App\Models\Chat\SessionChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FinishIdleSessionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        // Finish idle sessions
        SessionChat::whereNull('finished_at')
            ->where('persistent', false)
            ->where('last_message_at', '<', now()->subMinutes(5))
            ->each(
                function (SessionChat $sessionChat) {
                    Log::info('Finishing idle session', ['session_chat_id' => $sessionChat->id]);
                    $sessionChat->finish();
                }
            );
    }
}
