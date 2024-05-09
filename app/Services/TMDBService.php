<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TMDBService
{
    public const BASE_URL = 'https://api.themoviedb.org/3';
    public const IMAGE_BASE_URL = 'https://image.tmdb.org/t/p/';
    public const IMAGE_SIZE_W500 = 'w500';
    public const IMAGE_SIZE_ORIGINAL = 'original';

    public function getPosterUrl(string $uri, $size = self::IMAGE_SIZE_ORIGINAL): string
    {
        return 'https://image.tmdb.org/t/p/' . $size . $uri;
    }

    public function searchMovie(string $title, $language = 'en-US', $page = 1, $include_adult = true): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.tmdb.token'),
            'Accept' => 'application/json',
        ])->get('https://api.themoviedb.org/3/search/movie', [
            'query' => $title,
            'include_adult' => $include_adult,
            'language' => $language,
            'page' => $page,
        ]);

        $data = $response->json()['results'][0] ?? null;

        if (!$data) {
            return [];
        }

        $data['source'] = 'tmdb';

        $data['poster_url'] = $this->getPosterUrl($data['poster_path'], self::IMAGE_SIZE_W500);

        return $data;
    }
}
