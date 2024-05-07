<?php

namespace App\Models\Concerns;

use App\Models\Alias;

trait HasAliases
{
    /**
     * Customize name attribute for aliasable models.
     * @return string
     */
    public static function getAliasableNameAttribute(): string
    {
        return 'name';
    }

    public function aliases()
    {
        return $this->morphMany(Alias::class, 'aliasable');
    }

    public function scopeWhereAlias($query, string $name, string $operator = '=')
    {
        return $query->whereHas('aliases', function ($query) use ($operator, $name) {
            $query->where('name', $operator, $operator === 'like' ? "%$name%" : $name);
        });
    }

    public function scopeWhereNameOrAlias($query, string $name, string $operator = '=')
    {
        return $query->where(function ($query) use ($operator, $name) {
            $query->where(static::getAliasableNameAttribute(), $operator, $operator === 'like' ? "%$name%" : $name)
                ->orWhereHas('aliases', function ($query) use ($operator, $name) {
                    $query->where('name', $operator, $operator === 'like' ? "%$name%" : $name);
                });
        });
    }
}
