<?php

namespace App\Models\StoryElements;

use App\Models\Concerns\HasDataPoints;
use App\Models\Concerns\HasSummaries;
use App\Models\Story;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryTheme extends Model
{
    use SoftDeletes, HasFactory, HasDataPoints, HasSummaries;

    protected $fillable = [
        'story_id',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}
