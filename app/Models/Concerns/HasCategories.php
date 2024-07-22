<?php

namespace App\Models\Concerns;


use Illuminate\Database\Eloquent\Builder;

trait HasCategories
{
    public function scopeWhereCategory(Builder $query, string|array $nameOrNames): void
    {
        $query->whereHas('categories', function ($query) use ($nameOrNames) {
            if (is_array($nameOrNames)) {
                $query->whereIn('name', $nameOrNames);
            } else {
                $query->where('name', $nameOrNames);
            }
        });
    }
}
