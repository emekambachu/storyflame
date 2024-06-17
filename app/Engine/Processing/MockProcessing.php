<?php

namespace App\Engine\Processing;

use App\Models\Concerns\ModelWithComparableNames;
use App\Models\DataPoint;
use Illuminate\Foundation\Testing\Concerns\InteractsWithTime;

class MockProcessing extends BaseProcessing implements ProcessingInterface
{
    use InteractsWithTime;

    private array $responses;

    public function __construct(
        array $responses = []
    )
    {
        $this->setResponses($responses);
        parent::__construct(null);
    }

    public static function make(array $responses = []): MockProcessing
    {
        return new MockProcessing($responses);
    }

    public function addResponse(string $key, array|\Closure $response, array|null $filters = null, bool|null $persist = null): static
    {
        // check if response should be saved as list
        // either by providing filters or persist flag
        // or response already exists
        $is_list = $filters !== null || $persist !== null || isset($this->responses[$key]);

        if ($is_list) {
            if (isset($this->responses[$key])) {
                if (!array_is_list($this->responses[$key])) {
                    $this->responses[$key] = [
                        [
                            '_filters' => null,
                            '_persist' => null,
                            'response' => $this->responses[$key]
                        ]
                    ];
                }
            }

            $this->responses[$key][] = [
                '_filters' => $filters,
                '_persist' => $persist,
                'response' => $response
            ];
        } else {
            $this->responses[$key] = $response;
        }

        return $this;
    }

    public function setResponses(array $responses): void
    {
        $this->responses = $responses;
    }

    private function getFilteredResponse(string $key, array $attributes): array|string
    {
        $responses = $this->responses[$key];
        $matched = null;
        foreach ($responses as $response) {
            if (isset($response['_filters'])) {
                $filters = $response['_filters'];
                $match = true;
                foreach ($filters as $filterKey => $filterValue) {
                    if (!isset($attributes[$filterKey]) || $attributes[$filterKey] !== $filterValue) {
                        $match = false;
                        break;
                    }
                }
                if ($match) {
                    $matched = $response;
                    break;
                }
            }
        }

        if ($matched === null) {
            if (count($responses) === 0)
                throw new \Exception("No responses found for key $key");
            $matched = $responses[0];
        }

//        dump($matched);

        // check if response should be persisted
        if (isset($matched['_persist']) && $matched['_persist'] === true) {
            return $matched['response'] ?? $matched;
        }

        // remove this response from the list
        $index = array_search($matched, $responses);
        unset($responses[$index]);
        $this->responses[$key] = array_values($responses);

        return $matched['response'] ?? $matched;
    }

    public function simulateDelay(): void
    {
        $this->travel(1)->seconds();
    }

    private function getResponse(string $key, array $attributes): array|string
    {
//        $this->simulateDelay();
        if (!isset($this->responses[$key])) {
            throw new \Exception("Response key $key not found in mock responses");
        }

        if (!is_callable($this->responses[$key]) && array_is_list($this->responses[$key])) {
            return $this->getFilteredResponse($key, $attributes);
        }

        $this->saveUsage($key, [
            'model' => 'mock',
            'input_tokens' => rand(100, 10000),
            'output_tokens' => rand(100, 10000)
        ]);

        return is_callable($this->responses[$key]) ? $this->responses[$key]($attributes) : $this->responses[$key];
    }

    /**
     * @inheritDoc
     */
    public function rateResponse(string $question, string $answer): array
    {
        return $this->getResponse('rateResponse', ['question' => $question, 'answer' => $answer]);
    }

    /**
     * @inheritDoc
     */
    public function extractData(string $question, string $answer, array $groups): array
    {
        return $this->getResponse('extractData', ['question' => $question, 'answer' => $answer, 'groups' => $groups]);
    }

    /**
     * @inheritDoc
     */
    public function generateNextQuestion(string $engine, array $getChatHistory, array $availableDataPoints, $type = 'basic'): array
    {
        return $this->getResponse('generateNextQuestion', ['engine' => $engine, 'getChatHistory' => $getChatHistory, 'availableDataPoints' => $availableDataPoints, 'type' => $type]);
    }

    /**
     * @inheritDoc
     */
    public function generateTextData(array $data, string $type): string
    {
        return $this->getResponse('generateTextData', ['data' => $data, 'type' => $type]);
    }

    /**
     * @inheritDoc
     */
    public function analyzeConflict(DataPoint $dataPoint, mixed $oldValue, mixed $newValue)
    {
        return $this->getResponse('analyzeConflict', ['dataPoint' => $dataPoint, 'oldValue' => $oldValue, 'newValue' => $newValue]);
    }

    /**
     * @inheritDoc
     */
    public function extractCategories(string $question, string $answer): array
    {
        return $this->getResponse('extractCategories', ['question' => $question, 'answer' => $answer]);
    }

    /**
     * @inheritDoc
     */
    public function getSimilarElement(array $elementData, array $relatedElements): ?ModelWithComparableNames
    {
        foreach ($relatedElements as $relatedElement) {
            if ($relatedElement->getComparableNameAttribute() === $elementData['name']) {
                return $relatedElement;
            }
        }
        return null;
    }

    public function generateContextSwitchQuestion(array $elements, array $history)
    {
        return $this->getResponse('generateContextSwitchQuestion', ['elements' => $elements, 'history' => $history]);
    }

    public function isPositiveConfirmation(string $question, string $answer, array|null $selectElements = null): array
    {
        return $this->getResponse('isPositiveConfirmation', ['question' => $question, 'answer' => $answer, 'selectElements' => $selectElements]);
    }

}
