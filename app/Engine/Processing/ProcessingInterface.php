<?php

namespace App\Engine\Processing;

use App\Models\Concerns\ModelWithComparableNames;
use App\Models\DataPoint;

interface ProcessingInterface
{

    /**
     * Rate user's response to a question
     * @param string $question
     * @param string $answer
     * @return array
     */
    public function rateResponse(string $question, string $answer): array;

    /**
     * Extract data points from user's response
     * @param string $question
     * @param string $answer
     * @param array $groups
     * @return array
     */
    public function extractData(string $question, string $answer, array $groups): array;

    /**
     * Generate next question of a type
     * @param string $engine
     * @param array $getChatHistory
     * @param array $availableDataPoints
     * @param $type
     * @return array
     */
    public function generateNextQuestion(string $engine, array $getChatHistory, array $availableDataPoints, $type = 'basic'): array;

    /**
     * Generate some text data from extracted data
     * @param array $data
     * @param string $type
     * @return string
     */
    public function generateTextData(array $data, string $type): string;

    /**
     * Analyze conflict between old and new values
     * @param DataPoint $dataPoint
     * @param mixed $oldValue
     * @param mixed $newValue
     * @return mixed
     */
    public function analyzeConflict(DataPoint $dataPoint, mixed $oldValue, mixed $newValue);

    /**
     * @param string $question
     * @param string $answer
     * @return array
     */
    public function extractCategories(string $question, string $answer): array;

    /**
     * @param array $elementData
     * @param ModelWithComparableNames[] $relatedElements
     * @return ModelWithComparableNames|null
     */
    public function getSimilarElement(array $elementData, array $relatedElements): ?ModelWithComparableNames;
}
