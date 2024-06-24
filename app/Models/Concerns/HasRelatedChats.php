<?php

namespace App\Models\Concerns;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasRelatedChats
{
    public function chats(): MorphToMany
    {
        return $this->morphToMany(Chat::class, 'related', 'related_chats')
            ->withTimestamps();
    }
}
