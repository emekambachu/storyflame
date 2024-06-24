<?php

namespace App\Models\Concerns;


use Illuminate\Database\Eloquent\Builder;

trait HasCategories
{
    public function scopeWhereCategory(Builder $query, string $name): void
    {
        $query->whereHas('categories', function ($query) use ($name) {
            $query->where('name', $name);
        });
    }
}
