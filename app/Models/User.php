<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\HasDataPoints;
use App\Models\Concerns\HasSchemalessAttributes;
use App\Models\Concerns\HasSummaries;
use App\Models\Concerns\ModelWithId;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Paddle\Billable;
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
        'referred_by', // the person that referred you
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
        return $this->belongsTo(__CLASS__, 'referred_by_id', 'id');
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

    public function achievements(): BelongsToMany
    {
        return $this
            ->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot([
                'progress',
            ])
            ->withTimestamps();
    }

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function activeSubscriptions(){
        return $this->subscriptions()
            ->with('items.product', 'items.productPrice')
            ->where('next_billed_at', '>', now())
            ->orderBy('next_billed_at', 'asc')
            ->get();
    }

    public function getActiveSubscription()
    {
        return $this->activeSubscriptions()->first();
    }

    public function getActiveSubscriptionItem()
    {
        $subscription = $this->getActiveSubscription();

        if(!$subscription){
            return null;
        }

        return $subscription->items->where('status', 'active')->first();
    }

    public function getActiveSubscriptionInfo()
    {
        $subscription = $this->getActiveSubscription();

        if(!$subscription){
            return null;
        }

        $subscriptionItem = $this->getActiveSubscriptionItem();

        if(!$subscriptionItem){
            return null;
        }

        return [
            'subscription_id' => $subscription->paddle_id,
            'next_billed_at' => $subscription->next_billed_at,
            'product_id' => $subscriptionItem->product_id,
            'price_id' => $subscriptionItem->price_id,
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
