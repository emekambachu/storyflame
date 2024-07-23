<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\SubscriptionItem;
use App\Models\Transaction;
use App\Events\UserRegistrationEvent;
use App\Listeners\RegistrationEmailListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Paddle\Cashier;
use Laravel\Paddle\Subscription;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::useCustomerModel(Customer::class);
        Cashier::useSubscriptionModel(Subscription::class);
        Cashier::useTransactionModel(Transaction::class);
        Cashier::useSubscriptionItemModel(SubscriptionItem::class);

        Event::listen(
            UserRegistrationEvent::class,
            RegistrationEmailListener::class,
        );
    }
}
