<?php

namespace App\Observers;

use App\Jobs\SummarizeSessionJob;
use App\Models\Chat\SessionChat;

class SessionChatObserver
{
    public function creating(SessionChat $sessionChat): void
    {
        if (!$sessionChat->last_message_at)
            $sessionChat->last_message_at = now();
    }

    public function created(SessionChat $sessionChat): void
    {
    }

    public function updated(SessionChat $sessionChat): void
    {
        if ($sessionChat->finished_at && is_null($sessionChat->summarized_at))
        {
            $sessionChat->summarized_at = now();
            $sessionChat->save();
            SummarizeSessionJob::dispatch($sessionChat);
        }
    }

    public function deleted(SessionChat $sessionChat): void
    {
    }

    public function restored(SessionChat $sessionChat): void
    {
    }
}
