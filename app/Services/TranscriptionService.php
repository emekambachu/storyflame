<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class TranscriptionService
{
    public function transcribe(string|UploadedFile $path_or_file): string
    {
        if ($path_or_file instanceof UploadedFile) {
            $path_to_audio = $path_or_file->store('tmp');
        } else {
            $path_to_audio = $path_or_file;
        }

        $response = Http::post(config('app.processing_url') . '/transcribe', [
            'path' => storage_path('app/' . $path_to_audio),
        ]);

        $response->throw();

        return $response->json();
    }
}
