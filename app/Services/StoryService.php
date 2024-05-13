<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class StoryService
{
    private function parse(UploadedFile $file): array
    {
        $filename = now()->format('Y-m-d') . '-' . uniqid() . '.pdf';

        Storage::disk('local')->put('scripts/' . $filename, $file->get());


        $response = Http::post(config('app.processing_url') . '/script/parse', [
            'path' => storage_path('app/scripts/' . $filename),
        ]);

        $response->throw();

        Cache::set('parsed_script', $response->json());
        return Cache::get('parsed_script');
    }

    public function process(UploadedFile $file)
    {
        return $this->parse($file);
    }
}
