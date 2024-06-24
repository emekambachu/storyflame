<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes, HasFactory;

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
