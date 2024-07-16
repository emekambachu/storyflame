<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Models\SubscriptionDowngrade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSubscriptionDowngrades implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('ProcessSubscriptionDowngrades started at ' . now());

        SubscriptionDowngrade::markDowngradesReadyToProcess();

        $downgrades = SubscriptionDowngrade::getDowngradesToProcess();

        Log::info('Number of downgrades to process: ' . count($downgrades));

        foreach ($downgrades as $downgrade) {
            /** @var Subscription $subscription */
            $subscription = $downgrade->subscription;
            $result = $subscription->updateSubscription($downgrade->newProductPrice);
            Log::info('Subscription update result: ' . json_encode($result));
        }
    }
}
