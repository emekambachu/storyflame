<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Laravel\Paddle\Cashier;
use Laravel\Paddle\Concerns\Prorates;
use Laravel\Paddle\Payment;
use Laravel\Paddle\Subscription as PaddleSubscription;


class Subscription extends PaddleSubscription
{
    use HasFactory, Prorates;

    protected $fillable = [
        'type',
        'paddle_id',
        'status',
        'trial_ends_at',
        'paused_at',
        'ends_at',
        'next_billed_at'
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'paused_at' => 'datetime',
        'ends_at' => 'datetime',
        'next_billed_at' => 'datetime'
    ];

    public function billable(): MorphTo
    {
        // Define the inverse of the polymorphic relationship
        return $this->morphTo();
    }

    /**
     * Get the subscription items for the subscription.
     */
    public function items()
    {
        return $this->hasMany(SubscriptionItem::class);
    }

    /**
     * @return SubscriptionItem|null
     */
    public function activeItem(): ?SubscriptionItem
    {
        return $this->items()->where('status', 'active')->first();
    }

    /**
     * @return ProductPrice|null
     */
    public function activeProductPrice(): ?ProductPrice
    {
        return $this->activeItem()->productPrice;
    }

    /**
     * Defines the relationship between the current model and subscription downgrades.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function downgrades(): HasMany
    {
        return $this->hasMany(SubscriptionDowngrade::class);
    }

    /**
     * Get the approved downgrade for the given product price.
     *
     * @param ProductPrice $newProductPrice The new product price for the downgrade.
     * @return SubscriptionDowngrade|null The SubscriptionDowngrade instance.
     */
    public function getApprovedDowngrade(ProductPrice $newProductPrice): ?SubscriptionDowngrade
    {
        return $this->downgrades()
            ->where('new_product_price_id', $newProductPrice->id)
            ->where('is_ready_to_process', true)
            ->whereNull('downgraded_at')
            ->first();
    }

    /**
     * Get the pending downgrade for the given product price paddle_id.
     *
     * @param string $newPriceId The new price paddle_id for the downgrade.
     * @return SubscriptionDowngrade|null The SubscriptionDowngrade instance.
     */
    public function getPendingDowngrade(string $newPriceId): ?SubscriptionDowngrade
    {
        return $this->downgrades()
            ->whereHas('newProductPrice', function ($query) use ($newPriceId) {
                $query->where('paddle_id', $newPriceId);
            })
            ->where('is_ready_to_process', true)
            ->whereNull('downgraded_at')
            ->first();
    }

    /**
     * Creates a new subscription downgrade for the given subscription item and new product price.
     *
     * @param SubscriptionItem $subscriptionItem The subscription item being downgraded.
     * @param ProductPrice $newProductPrice The new product price for the downgrade.
     * @return SubscriptionDowngrade The created SubscriptionDowngrade instance.
     */
    public function createDowngrade(SubscriptionItem $subscriptionItem, ProductPrice $newProductPrice): SubscriptionDowngrade
    {
        return SubscriptionDowngrade::create([
            'user_id' => $this->billable->id,
            'subscription_id' => $this->id,
            'subscription_item_id' => $subscriptionItem->id,
            'new_product_id' => $newProductPrice->product_id,
            'new_product_price_id' => $newProductPrice->id,
            'downgrade_at' => $this->next_billed_at->subDay(),
        ]);
    }

    /**
     * Deletes the subscription downgrade if it exists for the given product price and is not one of the DowngradesToProcess.
     *
     * @param ProductPrice $newProductPrice The new product price to check for downgrades.
     * @return void
     */
    public function deleteDowngradeIfExists(ProductPrice $newProductPrice): void
    {
        $this->downgrades()
            ->where('new_product_price_id', $newProductPrice->id)
            ->where('is_ready_to_process', false)
            ->whereNull('downgraded_at')
            ->get()
            ->each(function (SubscriptionDowngrade $subscriptionDowngrade) {
                $subscriptionDowngrade->delete();
            });
    }

    /**
     * Deletes all pending subscription downgrades for a given subscription ID.
     *
     * @param int $subscriptionId The ID of the subscription to delete downgrades for.
     * @return void
     */
    public function deleteSubscriptionDowngrades(): void
    {
        $this->downgrades()
            ->where('is_ready_to_process', false)
            ->whereNull('downgraded_at')
            ->get()
            ->each(function (SubscriptionDowngrade $subscriptionDowngrade) {
                $subscriptionDowngrade->delete();
            });
    }

    /**
     * Updates the subscription based on the new product price and other conditions.
     *
     * @param ProductPrice $newProductPrice The new ProductPrice.
     * @return array The response with the updated subscription or error message.
     */
    public function updateSubscription(ProductPrice $newProductPrice): array
    {
        Log::info('IM UPDATING SUBSCRIPTION');
        /**
         * @var SubscriptionItem|null $subscriptionItem
         */
        $subscriptionItem = $this->billable->getActiveSubscriptionItem();
        if(!$subscriptionItem){
            return ['error' => 'No active subscription item found', 'status' => 404];
        }

        // If the subscriptionItem price_id is the same as the newProductPricePaddleId, return the subscription
        if ($subscriptionItem->price_id === $newProductPrice->paddle_id) {
            Log::info('THIS IS THE SAME... I DO NOTHING');
            return ['is_same' => true, 'subscription' => $this->fresh(), 'status' => 200];
        }

        Log::info('THIS IS NOT THE SAME');
        $currentProductPrice = ProductPrice::where('paddle_id', $subscriptionItem->price_id)->first();

        $isSameProduct = $currentProductPrice->product_id === $newProductPrice->product_id;

        $approvedDowngrade = $this->getApprovedDowngrade($newProductPrice);

        $isUpgradingProduct = !$isSameProduct && $currentProductPrice->price < $newProductPrice->price;
        $isDowngradingProduct = !$isSameProduct && $currentProductPrice->price > $newProductPrice->price;

        $isUpgradingProductPrice = $isSameProduct && $currentProductPrice->price < $newProductPrice->price;
        $isDowngradingProductPrice = $isSameProduct && $currentProductPrice->price > $newProductPrice->price;

        Log::info('Swapping subscription to new ProductPrice.paddle_id: ' . $newProductPrice->paddle_id);

        try {
            $this->deleteDowngradeIfExists($newProductPrice);
            $this->deleteSubscriptionDowngrades();

            switch (true) {
                case $approvedDowngrade:
                case $isUpgradingProductPrice:
                case $isUpgradingProduct:
                    $this->prorateImmediately()->swap($newProductPrice->paddle_id);
                    $this->update(['next_billed_at' => Carbon::now()->addMonths($newProductPrice->interval === 'month' ? 1 : 12)]);
                    return ['subscription' => $this->fresh(), 'status' => 200, 'message' => 'Subscription upgraded successfully'];
                case $isDowngradingProductPrice:
                case $isDowngradingProduct:
                    $downgrade = $this->createDowngrade($subscriptionItem, $newProductPrice);
                    return ['subscription' => $this->fresh(), 'status' => 200, 'message' => 'Downgrade scheduled', 'downgrade' => $downgrade];
            }
        } catch (\Exception $e) {
            return ['error' => 'Failed to update subscription: ' . $e->getMessage(), 'status' => 500];
        }
        return ['error' => 'Unexpected updatedSubscription error occurred', 'status' => 500];
    }
}
