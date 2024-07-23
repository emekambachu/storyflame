<?php

namespace App\Engine\Context;

use App\Models\Achievement;
use App\Models\Chat;
use App\Models\Chat\SessionChat;
use App\Models\ChatMessage;
use App\Models\Concerns\ModelWithComparableNames;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

interface ContextInterface
{

    public function getModel();

    public function stories(): ?HasMany;

    public function characters(): ?HasMany;

    public function plots(): ?HasMany;

    public function sequences(): ?HasMany;

    public function settings(): ?HasMany;

    public function themes(): ?HasMany;

    /**
     * @param string $elementType
     * @return ModelWithComparableNames[]
     */
    public function getElements(string $elementType): array;

    public function addElement(string $elementType, array $elementData): ?ModelWithComparableNames;
}
