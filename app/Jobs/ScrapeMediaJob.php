<?php

namespace App\Jobs;

use App\Models\Media;
use App\Services\ImageService;
use App\Services\TMDBService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapeMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Media $media)
    {
    }

    public function handle(
        TMDBService $tmdbService,
        ImageService $imageService
    ): void
    {
        $response = $tmdbService->searchMovie($this->media->title);

        $this->media->update([
            'released' => $response['release_date'],
            'extra_attributes' => $response,
        ]);

        $this->media->images()->create([
            'path' => $imageService->downloadImage($response['poster_url']),
            'group' => 'poster',
        ]);

        $this->media->save();
    }
}
