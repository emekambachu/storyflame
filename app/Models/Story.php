<?php

namespace App\Models;

use App\Models\Concerns\HasRelatedChats;
use App\Models\Concerns\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Story extends Model
{
    use SoftDeletes, HasFactory, HasUuids, HasRelatedChats, HasSchemalessAttributes;

    protected $fillable = [
        'name',
        'user_id',
        'extra_attributes'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
