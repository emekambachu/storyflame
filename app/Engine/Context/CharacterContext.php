<?php

namespace App\Engine\Context;

use App\Engine\Config\CharacterEngineConfig;
use App\Models\Character;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CharacterContext extends BaseContext implements ContextInterface
{
    function getContextClass(): ?string
    {
        return Character::class;
    }


    public function __construct($model = null)
    {
        parent::__construct($model);
        $this->config = new CharacterEngineConfig();
    }

    public function stories(): HasMany
    {
        // TODO: Implement stories() method.
    }

    public function characters(): HasMany
    {
        // TODO: Implement characters() method.
    }

    public function plots(): HasMany
    {
        // TODO: Implement plots() method.
    }

    public function sequences(): HasMany
    {
        // TODO: Implement sequences() method.
    }

    protected function getCurrentData(): array
    {
        // TODO: Implement getCurrentData() method.
    }

    protected function getContextName(): string
    {
        // TODO: Implement getContextName() method.
    }

    protected function getContextGoal(): string
    {
        // TODO: Implement getContextGoal() method.
    }


}
