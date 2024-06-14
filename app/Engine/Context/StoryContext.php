<?php

namespace App\Engine\Context;

use App\Engine\Config\StoryEngineConfig;
use App\Engine\Context\ContextInterface;
use App\Models\Character;
use App\Models\Story;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @template-extends BaseContext<Story>
 */
class StoryContext extends BaseContext implements ContextInterface
{
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


    /**
     * @inheritDoc
     */
    public function getStories(): array
    {

    }

    /**
     * @inheritDoc
     */
    public function getCharacters(): array
    {
        return $this->getModel()->characters()->get()->all();
    }

    public function addStory(Story $story): Story
    {
        // TODO: Implement addStory() method.
    }

    public function addCharacter(array $character): Character
    {
        return $this->getModel()->characters()->create($character);
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


}
