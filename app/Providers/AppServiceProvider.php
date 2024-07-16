<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Paddle\Cashier;

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
    }
}
