<?php

namespace App\Services;

use App\Models\Media;

class MediaService
{
    public function getMovieByTitle(string $title): Media
    {
        if ($media = Media::whereNameOrAlias($title, 'like')->first()) {
            return $media;
        }

        // TODO: schedule a job to fetch the movie from an API

        return Media::create([
            'title' => $title,
        ]);
    }
}
