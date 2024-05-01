<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TranscriptionService
{
    public function transcribe(string $path_to_audio): string
    {
        $response = Http::post('python:3000/transcribe', [
            'path' => storage_path('app/' . $path_to_audio),
        ]);

        $response->throw();

        return $response->json();
    }
}
