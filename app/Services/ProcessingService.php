<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessingService
{
    public static function getProcessingUrl()
    {
        return config('app.processing_url');
    }

    public static function sendRequest(string $method, string $uri, array $data = []): \Illuminate\Http\Client\Response
    {
        Log::info($uri, $data);
        $response = Http::$method(
            static::getProcessingUrl() . $uri,
            $data
        );

        $response->throw();

        return $response;
    }

    public static function rateResponse(string $question, string $answer): array
    {
        return static::sendRequest('post', '/api/conversation/rate', [
            'question' => $question,
            'answer' => $answer
        ])->json();
    }

    public static function isValidData($value)
    {
        return !empty($value) && !in_array(strtolower($value), ['no', 'none', 'nothing', 'n/a', 'n.a.', 'n.a', 'na']);
    }

    public static function filterData(array $data): array
    {
        $filtered = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $filtered[$key] = static::filterData($value);
            } else if (static::isValidData($value)) {
                $filtered[$key] = $value;
            }
        }
        return $filtered;
    }

    public static function extractData(string $question, string $answer, array $groups): array
    {
        return static::filterData(
            static::sendRequest('post', '/api/conversation/extract', [
                'question' => $question,
                'answer' => $answer,
                'groups' => $groups
            ])->json()
        );
    }

    public static function generateNextQuestion(string $engine, array $getChatHistory, array $availableDataPoints, $type = 'basic'): array
    {
        return static::sendRequest('post', '/api/conversation/next', [
            'engine' => $engine,
            'chat_history' => $getChatHistory,
            'available_data_points' => $availableDataPoints,
            'type' => $type
        ])->json();
    }

    public static function generateTextData(array $data, string $type): string
    {
        return static::sendRequest('post', '/api/conversation/generate', [
            'extracted' => $data,
            'type' => $type
        ])->json()["generated"];
    }

    public static function analyzeConflict(\App\Models\DataPoint $dataPoint, mixed $oldValue, mixed $newValue)
    {
        return static::sendRequest('post', '/api/conversation/conflict', [
            'data_point' => $dataPoint->toProcessingArray(),
            'old_value' => $oldValue,
            'new_value' => $newValue
        ])->json();
    }
}
