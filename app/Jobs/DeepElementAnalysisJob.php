<?php

namespace App\Jobs;

use App\Engine\ConversationEngine;
use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class DeepElementAnalysisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @param ConversationEngine $engine engine to use
     * @param array $extracted extracted data to exclude from deep extraction
     */
    public function __construct(
        private readonly ChatMessage        $question,
        private readonly ChatMessage        $answer,
        private readonly ConversationEngine $engine,
        private readonly array              $extracted
    )
    {
    }

    public function handle(): void
    {
        // categories from response
        $categories = $this->engine->extractCategories($this->question, $this->answer);
        // get elements from context
        $context = $this->engine->getContext();

        $changes = [];

        // cycle through categories
        foreach ($categories as $category) {
            if (
                !isset($category['type']) ||
                !isset($category['elements'])
            ) {
                throw new \Exception('Invalid category format, ' . $category);
            }
            $categoryType = $category['type'];

            // TODO: remove this check
            if (!in_array($categoryType, ['Character']))
                continue;

            // related elements in this category
            $relatedElements = $context->getElements($categoryType);

            foreach ($category['elements'] as $element) {
                // find most similar element
                $similarElement = $this->engine->getProcessing()->getSimilarElement(
                    $element,
                    $relatedElements
                );

                // if there are no similar elements
                if (!$similarElement) {
                    $created = $context->addElement(
                        $categoryType,
                        $element
                    );
                    $changes[] = [
                        'action' => 'add',
                        'element' => $created,
                        'category' => $categoryType
                    ];
                } else {
                    dd('confirm aliasing or merging of elements');
                }
            }
        }

        // group changes by category
        $groupedChanges = [];
        foreach ($changes as $change) {
            $groupedChanges[$change['category']][] = $change;
        }

        // get the longest group
        $longestGroup = null;
        $longestGroupLength = 0;
        foreach ($groupedChanges as $category => $group) {
            if (count($group) > $longestGroupLength) {
                $longestGroup = $group;
                $longestGroupLength = count($group);
            }
        }

        $count = count($longestGroup);
        $q = $this->engine->getProcessing()->generateNextQuestion(
            $this->engine->getEngineName(),
            $this->engine->getStorage()->getHistory(),
            [
                'category' => $longestGroup[0]['category'],
                'count' => $count
            ],
            'switch_element'
        );

        $this->engine->getStorage()
            ->queueQuestion([
                    'type' => 'confirm_switch_element',
                    'content' => $q['question'],
                    'extra_attributes' => [
                        'title' => $q['message'],
                        'element' => $longestGroup[0]['category'],
                    ]
                ]
            );
    }
}
