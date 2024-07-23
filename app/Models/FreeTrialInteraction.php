<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreeTrialInteraction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'total_interactions_used',
        'daily_interactions_used',
        'last_interaction_date',
        'images_generated'
    ];

    protected $casts = [
        'last_interaction_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incrementTotalInteractionsUsed()
    {
        $this->total_interactions_used++;
        $this->save();
    }

    public function incrementDailyInteractionsUsed()
    {
        $this->daily_interactions_used++;
        $this->save();
    }

    public function incrementImagesGenerated()
    {
        $this->images_generated++;
        $this->save();
    }

    public function setLastInteractionDate()
    {
        $this->last_interaction_date = now();
        $this->save();
    }

    public function resetDailyInteractionsUsed()
    {
        $this->daily_interactions_used = 0;
        $this->save();
    }
}
