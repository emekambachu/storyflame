<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class TranscriptionController extends Controller
{
    public function transcribe(Request $request)
    {
        $request->validate([
            'audio' => ['required', 'file', 'mimes:webm'],
        ]);

        $audio = $request->file('audio');

        $path = $audio->store('tmp');

        $response = Http::post('python:3000/transcribe', [
            'path' => storage_path('app/' . $path),
        ]);

        $response->throw();

        return response()->json($response->json());
    }
}
