<?php

namespace App\Engine\Processing;

use App\Models\Concerns\ModelWithComparableNames;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocalPythonProcessing implements ProcessingInterface
{
    public function getProcessingUrl()
    {
        return config('app.processing_url');
    }

    public function sendRequest(string $method, string $uri, array $data = []): \Illuminate\Http\Client\Response
    {
        Log::info($uri, $data);
        $response = Http::$method(
            $this->getProcessingUrl() . $uri,
            $data
        );

        $response->throw();

        return $response;
    }

    public function rateResponse(string $question, string $answer): array
    {
        return $this->sendRequest('post', '/api/conversation/rate', [
            'question' => $question,
            'answer' => $answer
        ])->json();
    }

    private function isValidData($value): bool
    {
        return !empty($value) && !in_array(strtolower($value), ['no', 'none', 'nothing', 'n/a', 'n.a.', 'n.a', 'na']);
    }

    /**
     * TODO: move to a helper class
     * @param array $data
     * @return array
     */
    public function filterData(array $data): array
    {
        $filtered = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $filtered[$key] = $this->filterData($value);
            } else if ($this->isValidData($value)) {
                $filtered[$key] = $value;
            }
        }
        return $filtered;
    }

    public function extractData(string $question, string $answer, array $groups): array
    {
        return $this->filterData(
            $this->sendRequest('post', '/api/conversation/extract', [
                'question' => $question,
                'answer' => $answer,
                'groups' => $groups
            ])->json()
        );
    }

    public function generateNextQuestion(string $engine, array $getChatHistory, array $availableDataPoints, $type = 'basic'): array
    {
        return $this->sendRequest('post', '/api/conversation/next', [
            'engine' => $engine,
            'chat_history' => $getChatHistory,
            'available_data_points' => $availableDataPoints,
            'type' => $type
        ])->json();
    }

    public function generateTextData(array $data, string $type): string
    {
        return $this->sendRequest('post', '/api/conversation/generate', [
            'extracted' => $data,
            'type' => $type
        ])->json()["generated"];
    }

    public function analyzeConflict(\App\Models\DataPoint $dataPoint, mixed $oldValue, mixed $newValue)
    {
        return $this->sendRequest('post', '/api/conversation/conflict', [
            'data_point' => $dataPoint->toProcessingArray(),
            'old_value' => $oldValue,
            'new_value' => $newValue
        ])->json();
    }

    public function extractCategories(string $question, string $answer): array
    {
        throw new \Exception('Not implemented');
    }

    public function getSimilarElement(array $elementData, array $relatedElements): ?ModelWithComparableNames
    {
        // TODO: Implement getSimilarElement() method.
    }
}
