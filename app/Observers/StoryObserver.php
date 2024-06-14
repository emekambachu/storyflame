<?php

namespace App\Observers;

use App\Models\Story;

class StoryObserver
{
    public function creating(Story $story): void
    {
        if (empty($story->name)) {

            $drafts = Story::where('name', 'like', 'Draft #%')
                ->orderBy('name', 'desc')
                ->pluck('name');

            $highestDraftNumber = 0;
            foreach ($drafts as $draft) {
                $draftNumber = (int) str_replace('Draft #', '', $draft);
                if ($draftNumber > $highestDraftNumber) {
                    $highestDraftNumber = $draftNumber;
                }
            }

            $story->name = 'Draft #' . ($highestDraftNumber + 1);
        }
    }

    public function created(Story $story): void
    {
    }

    public function updated(Story $story): void
    {
    }

    public function deleted(Story $story): void
    {
    }

    public function restored(Story $story): void
    {
    }
}
