<?php

namespace App\Models\StoryElements;

use App\Models\Story;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryPlot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'story_id',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}
