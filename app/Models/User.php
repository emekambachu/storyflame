<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\HasDataPoints;
use App\Models\Concerns\HasSchemalessAttributes;
use App\Models\Concerns\HasSummaries;
use App\Models\Concerns\ModelWithId;
use App\Models\Referral\ReferralType;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Paddle\Billable;
use Laravel\Paddle\Cashier;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements ModelWithId
{
    use HasFactory, Notifiable, HasApiTokens, HasSchemalessAttributes, HasDataPoints, HasSummaries, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'bio',
        'email',
        'password',
        'attributes',
        'referral_code',
        'referred_by_code',
        'paddle_id',
        'trial_ends_at',
        'last_login',
        'referred_by', // the person who referred you
        'referral_code', // your referral code
        'is_verified',
        'last_login',
        'paddle_id',
        'trial_ends_at',
    ];

    protected $dates = [
        'trial_ends_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function createUniqueReferralCode(): string
    {
        $referralCode = substr(md5($this->email), 0, 8);
        $referralCodeExists = User::where('referral_code', $referralCode)->exists();
        if ($referralCodeExists) {
            return $this->createUniqueReferralCode();
        }
        return $referralCode;
    }

    /**
     * @param string $referralCode
     * @return User|null
     */
    public static function findByReferralCode(string $referralCode): ?User
    {
        return User::where('referral_code', $referralCode)->first();
    }

    public function verifyEmail(): void
    {
        if ($this->email_verified_at === null) {
            $this->email_verified_at = now();
            $this->save();
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function (User $user) {
            if ($user->isDirty('name') && $user->paddle_id && $user->name !== null && $user->name !== '') {
                $user->updatePaddleName();
            }
        });
    }

    public function updatePaddleName()
    {
        try {
            $response = Cashier::api('PATCH', "customers/{$this->paddle_id}", [
                'name' => $this->name,
            ]);
            return $response;
        } catch (\Exception $e) {
            Log::error("Failed to update Paddle name for user {$this->id}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'id', 'imageable_id');
    }

    public function referred_by(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'referred_by', 'id');
    }

    public function user_products()
    {
        return $this->belongsToMany(Product::class, 'user_products', 'user_id', 'product_id')
            ->withPivot([
                'status',
                'trial_ends_at',
                'ends_at',
            ])
            ->withTimestamps();
    }

    public function referral_types(){
        return $this->belongsToMany(ReferralType::class, 'user_referral_types', 'user_id', 'referral_type_id')
            ->withPivot([
                'start_date',
                'end_date',
                'is_active',
            ])
            ->withTimestamps();
    }

    public function user_referrals()
    {
        return $this->belongsToMany(__CLASS__, 'user_referrals', 'referrer_id', 'receiver_id')
            ->withPivot([
                'recipient_discount_amount',
                'referrer_discount_amount',
                'referrer_commission_amount',
                'discount_ends_at',
                'commission_ends_at',
            ])
            ->withTimestamps();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    /**
     * Get the stories for the user.
     */
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    public function characters(): HasManyThrough
    {
        return $this->hasManyThrough(Character::class, Story::class);
    }

    public function customer()
    {
        return $this->morphOne(Customer::class, 'billable');
    }

    public function developmentReports(): HasMany
    {
        return $this->hasMany(UserDevelopmentReport::class);
    }

    /**
     * @return HasMany
     */
    public function getAvailableDevelopmentReports(): HasMany
    {
        return $this->developmentReports()
            ->where('status', UserDevelopmentReport::STATUS_AVAILABLE);
    }

    public function verificationCodes(): HasMany
    {
        return $this->hasMany(VerificationCode::class);
    }

    public function favoriteMovies(): MorphToMany
    {
        return $this->morphedByMany(
            Media::class,
            'favorite',
            'user_favorites',
        );
    }

    /**
     * @return BelongsToMany
     */
    public function achievements(): BelongsToMany
    {
        return $this
            ->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot([
                'progress',
            ])
            ->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    /**
     * @return HasMany
     */
    public function aiImageSlots(): HasMany
    {
        return $this->hasMany(AIImageSlot::class);
    }

    public function activeSubscriptions()
    {
        return $this->subscriptions()
            ->with('items.product', 'items.productPrice')
            ->where('status', 'active')
            ->whereNotNull('next_billed_at')
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->orderBy('next_billed_at', 'desc')
            ->get();
    }

    /**
     * Retrieves the active subscription for the current user.
     *
     * @return Subscription|null The active subscription, or null if none exists.
     */
    public function getActiveSubscription(): ?Subscription
    {
        return $this->activeSubscriptions()->first();
    }

    /**
     * Retrieves the active subscription item for the current user.
     *
     * @return SubscriptionItem|null The active subscription item, or null if none exists.
     */
    public function getActiveSubscriptionItem(): ?SubscriptionItem
    {
        $subscription = $this->getActiveSubscription();

        if (!$subscription) {
            return null;
        }

        return $subscription->items->where('status', 'active')->first();
    }

    /**
     * Retrieves the active subscription information for the current user.
     *
     * @return array|null The active subscription information, or null if none exists.
     */
    public function getActiveSubscriptionInfo(): ?array
    {
        /** @var Subscription|null $subscription */
        $subscription = $this->getActiveSubscription();

        if (!$subscription) {
            return null;
        }

        $subscriptionItem = $this->getActiveSubscriptionItem();

        if (!$subscriptionItem) {
            return null;
        }

        $downgrade = $subscription->downgrades()
            ->whereNull('downgraded_at')
            ->where('downgrade_at', '>', now())
            ->first();

        return [
            'subscription_id' => $subscription->paddle_id,
            'next_billed_at' => $subscription->next_billed_at,
            'product_id' => $subscriptionItem->product_id,
            'price_id' => $subscriptionItem->price_id,
            'downgrade_at' => $downgrade ? $downgrade->downgrade_at : null,
            'new_product_price_id' => $downgrade ? $downgrade->newProductPrice->paddle_id : null,
            'ends_at' => $subscription->ends_at,
            'available_report_count' => $this->getAvailableDevelopmentReports()->count(),
        ];
    }

//    public function createSubscription($name, $plan)
//    {
//        return $this->subscriptions()->create([
//            'name' => $name,
//            'plan' => $plan,
//            'quantity' => 1,
//            'ends_at' => now()->addDays(30),
//        ]);
//    }
}
