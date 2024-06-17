<?php

namespace App\Engine\Processing;

use App\Models\Concerns\ModelWithComparableNames;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocalPythonProcessing extends BaseProcessing implements ProcessingInterface
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
            'current_context' => $this->context->getCurrentContext($question, $answer),
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
                'current_context' => $this->context->getCurrentContext($question, $answer),
                'topic_data_points' => $groups
            ])->json()
        );
    }

    public function generateNextQuestion(string $engine, array $getChatHistory, array $availableDataPoints, $type = 'basic'): array
    {
        return $this->sendRequest('post', '/api/conversation/next', [
            'engine' => $engine,
            'current_context' => $this->context->getCurrentContext(),
            'history' => $getChatHistory,
            'achievements' => $availableDataPoints,
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
        throw new \Exception('Not implemented');
    }

    public function generateContextSwitchQuestion(array $elements, array $history)
    {
        return $this->sendRequest('post', '/api/conversation/switch', [
            'elements' => $elements,
            'history' => $history
        ])->json();
    }

    public function isPositiveConfirmation(string $question, string $answer, array|null $selectElements = null): array
    {
        return $this->sendRequest('post', '/api/conversation/confirm', [
            'question' => $question,
            'answer' => $answer,
            'select_elements' => $selectElements
        ])->json();
    }
}
