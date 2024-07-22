<?php

namespace App\Models;

use App\Models\Summary\Summary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\UserDataPoint;
use App\Models\UserSummary;
use App\Models\DataPoint;

class ImageTypeSchema extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'image_type_id',
        'schemaable_id',
        'schemaable_type',
        'is_required'
    ];

    /**
     * @return HasMany
     */
    public function imageType(): HasMany
    {
        return $this->hasMany(ImageType::class);
    }

    /**
     * @return MorphTo
     */
    public function schemaable(): MorphTo
    {
        return $this->morphTo();
    }

    public function userDataPoints()
    {
        return $this->hasMany(UserDataPoint::class, 'data_point_id', 'schemaable_id')
            ->where('schemaable_type', DataPoint::class);
    }

    public function userSummaries()
    {
        return $this->hasMany(UserSummary::class, 'summary_id', 'schemaable_id')
            ->where('schemaable_type', Summary::class);
    }
}
