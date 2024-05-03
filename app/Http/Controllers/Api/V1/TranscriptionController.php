<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TranscriptionController extends Controller
{
    public function transcribe(Request $request)
    {
        $request->validate([
            'audio' => ['required', 'file', 'mimes:mp4,wav,webm'],
        ]);


        $audio = $request->file('audio');

        $path = $audio->store('tmp');
        return $this->errorResponse('error', 500, 'error', ['path' => $path]);

        $response = Http::post(config('app.processing_url') . '/transcribe', [
            'path' => storage_path('app/' . $path),
        ]);

        $response->throw();

        return $this->successResponse('success', [
            'transcription' => $response->json(),
        ]);
    }
}
