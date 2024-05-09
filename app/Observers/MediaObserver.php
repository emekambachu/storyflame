<?php

namespace App\Observers;

use App\Jobs\ScrapeMediaJob;
use App\Models\Media;

class MediaObserver
{
    public function created(Media $media): void
    {
        ScrapeMediaJob::dispatch($media);
    }

    public function updated(Media $media): void
    {
    }

    public function deleted(Media $media): void
    {
    }

    public function restored(Media $media): void
    {
    }
}
