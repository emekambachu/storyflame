<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface ModelWIthRelatedChats
{
    /**
     * Get all chats related to the model
     * @return MorphToMany
     */
    public function chats(): MorphToMany;
}
