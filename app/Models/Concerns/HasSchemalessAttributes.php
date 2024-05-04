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
	public function initializeHasSchemalessAttributes(): void
	{
		$this->casts['extra_attributes'] = SchemalessAttributes::class;
	}

	public function scopeWithExtraAttributes(): Builder
	{
		return $this->extra_attributes->modelScope();
	}
}
