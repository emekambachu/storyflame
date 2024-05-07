<?php

namespace App\Models;

use App\Models\Concerns\HasAliases;
use App\Models\Concerns\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes, HasUuids, HasSchemalessAttributes, HasAliases;

    public static function getAliasableNameAttribute(): string
    {
        return 'title';
    }

    protected $fillable = [
        'title',
        'poster',
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
