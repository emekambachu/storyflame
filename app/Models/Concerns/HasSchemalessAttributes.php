<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

/**
 * @mixin Model
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes extra_attributes
 */
trait HasSchemalessAttributes
{
    protected static string $schemalessColumn = 'extra_attributes';

    public function getSchemalessColumn(): string
    {
        return static::$schemalessColumn;
    }

    public function initializeHasSchemalessAttributes(): void
    {
        $this->casts[$this->getSchemalessColumn()] = SchemalessAttributes::class;
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->{$this->getSchemalessColumn()}->modelScope();
    }
}
