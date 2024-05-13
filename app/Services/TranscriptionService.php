<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TranscriptionService
{
    public function transcribe(string|UploadedFile $path_or_file): string
    {
        if ($path_or_file instanceof UploadedFile) {
            $filename = now()->format('Y-m-d') . '-' . uniqid() . '.' . $path_or_file->extension();
            Storage::disk('local')->put('audio/' . $filename, $path_or_file->get());
            $path_to_audio = storage_path('app/audio/' . $filename);
        } else {
            $path_to_audio = $path_or_file;
        }

        $response = Http::post(config('app.processing_url') . '/transcribe', [
            'path' => $path_to_audio,
        ]);

        $response->throw();

        return $response->json();
    }
}
