<?php

namespace App\Engine\Context;

use App\Engine\Config\CharacterEngineConfig;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CharacterContext extends BaseContext implements ContextInterface
{
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
}
