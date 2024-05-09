<?php

namespace App\Models;

use App\Models\Concerns\HasAliases;
use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSchemalessAttributes;
use App\Observers\MediaObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([MediaObserver::class])]
class Media extends Model
{
    use SoftDeletes, HasUuids, HasSchemalessAttributes, HasAliases, HasImages;

    public static function getAliasableNameAttribute(): string
    {
        return 'title';
    }

    protected $fillable = [
        'title',
        'released',
        'extra_attributes',
    ];

    protected function casts(): array
    {
        return [
            'released' => 'timestamp',
        ];
    }
}
