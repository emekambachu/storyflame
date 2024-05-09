<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'path',
        'group',
        'imageable_id',
        'imageable_type',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
