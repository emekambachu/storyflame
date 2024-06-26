<?php

namespace App\Engine\Context;

use App\Engine\Config\StoryEngineConfig;
use App\Models\Character;
use App\Models\Story;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @template-extends BaseContext<Story>
 */
class StoryContext extends BaseContext implements ContextInterface
{
    function getContextClass(): ?string
    {
        return Story::class;
    }


    public function __construct($model = null)
    {
        parent::__construct($model);
        $this->config = new StoryEngineConfig();
    }

    /**
     * @return Story
     */
    public function getModel(): Story
    {
        return $this->model;
    }

    public function stories(): HasMany
    {
        return $this->getModel()->user->stories();
    }

    public function characters(): HasMany
    {
        return $this->getModel()->characters();
    }

    public function plots(): HasMany
    {
        // TODO: Implement plots() method.
    }

    public function sequences(): HasMany
    {
    }

    protected function getCurrentData(): array
    {
        return [
            'Story' => [
                [
                    $this->getModel()
                        ->dataPointsToArray()
                ]
            ],
            'Character' => [
                $this->characters()
                    ->get()
                    ->map(fn($item) => $item->dataPointsToArray())
            ]
        ];
    }

    protected function getContextName(): string
    {
        return $this->getModel()->name ?? 'New Story';
    }

    protected function getContextGoal(): string
    {
        return 'Learn more about the story and its characters.';
    }
}
