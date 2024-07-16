<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class SubscriptionDowngrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'subscription_item_id',
        'new_product_id',
        'new_product_price_id',
        'downgrade_at',
        'downgraded_at',
        'is_ready_to_process'
    ];

    protected $dates = [
        'downgrade_at',
        'downgraded_at'
    ];

    protected $casts = [
        'is_ready_to_process' => 'boolean'
    ];

    /**
     * Defines the relationship to the User model.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Defines the relationship to the Subscription model.
     *
     * @return BelongsTo
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Defines the relationship to the SubscriptionItem model.
     *
     * @return BelongsTo
     */
    public function subscriptionItem(): BelongsTo
    {
        return $this->belongsTo(SubscriptionItem::class);
    }

    /**
     * Defines the relationship to the new Product model.
     *
     * @return BelongsTo
     */
    public function newProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'new_product_id');
    }

    /**
     * Defines the relationship to the new ProductPrice model.
     *
     * @return BelongsTo
     */
    public function newProductPrice(): BelongsTo
    {
        return $this->belongsTo(ProductPrice::class, 'new_product_price_id');
    }

    /**
     * Scope a query to only include active downgrades.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('downgraded_at');
    }

    /**
     * Scope a query to only include downgrades that are ready to be processed.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeReadyToProcess(Builder $query): Builder
    {
        return $query->where('is_ready_to_process', true);
    }

    /**
     * Scope a query to only include inactive downgrades.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->whereNotNull('downgraded_at');
    }

    /**
     * Marks the subscription downgrade as downgraded.
     *
     * @return void
     */
    public function downgrade(): void
    {
        $this->update([
            'downgraded_at' => now(),
            'is_ready_to_process' => false
        ]);
    }

    /**
     * Checks if the subscription downgrade is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return is_null($this->downgraded_at);
    }

    /**
     * Checks if the subscription downgrade is ready to be processed.
     *
     * @return bool
     */
    public function isReadyToProcess(): bool
    {
        return $this->is_ready_to_process;
    }

    /**
     * Checks if the subscription downgrade is inactive.
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return !is_null($this->downgraded_at);
    }

    /**
     * Marks all downgrades that are ready to be processed.
     *
     * @return void
     */
    public static function markDowngradesReadyToProcess(): void
    {
        $dateToProcess = now()->addDay();
        Log::info('Getting downgrades to process for date: ' . $dateToProcess);

        self::where('downgrade_at', '<=', $dateToProcess)
            ->active()
            ->update(['is_ready_to_process' => true]);
    }

    /**
     * Retrieves the downgrades that need to be processed.
     *
     * @return Collection|static[]
     */
    public static function getDowngradesToProcess(): Collection
    {
        return self::active()->readyToProcess()->get();
    }
}
