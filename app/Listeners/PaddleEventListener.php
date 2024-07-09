<?php

namespace App\Listeners;

use App\Models\User;
use Cassandra\Custom;
use Illuminate\Support\Facades\Log;
use Laravel\Paddle\Customer;
use Laravel\Paddle\Events\WebhookReceived;

class PaddleEventListener
{
    /**
     * Handle received Paddle webhooks.
     */
    public function handle(WebhookReceived $event): void
    {
        Log::info('Paddle event received: ' . $event->payload['event_type']);
        Log::info('Paddle payload: ' . json_encode($event->payload));

        $data = $event->payload['data'];
        Log::info('Data is ' . json_encode($data));


        switch($event->payload['event_type']) {
            case 'customer.created':
                $this->handleCustomerCreated($event);
                break;
            case 'customer.updated':
                $this->handleCustomerUpdated($event);
                break;
//            case 'discount.created':
//                $this->handleDiscountCreated($event);
//                break;
//            case 'discount.updated':
//                $this->handleDiscountUpdated($event);
//                break;
//            case 'product.created':
//                $this->handleProductCreated($event);
//                break;
//            case 'product.updated':
//                $this->handleProductUpdated($event);
//                break;
//            case 'price.created':
//                $this->handlePriceCreated($event);
//                break;
//            case 'price.updated':
//                $this->handlePriceUpdated($event);
//                break;
//            case 'subscription.created':
//                $this->handleSubscriptionCreated($event);
//                break;
//            case 'subscription.updated':
//                $this->handleSubscriptionUpdated($event);
//                break;
//            case 'subscription.cancelled':
//                $this->handleSubscriptionCancelled($event);
//                break;
//            case 'subscription.payment_succeeded':
//                $this->handleSubscriptionPaymentSucceeded($event);
//                break;
//            case 'subscription.payment_failed':
//                $this->handleSubscriptionPaymentFailed($event);
//                break;
//            case 'transaction.created':
//                $this->handleTransactionCreated($event);
//                break;
//            case 'transaction.updated':
//                $this->handleTransactionUpdated($event);
//                break;
//            case 'transaction.completed':
//                $this->handleTransactionCompleted($event);
//                break;
        }
    }

    private function handleCustomerCreated(WebhookReceived $event): void
    {
        $data = $event->payload['data'];
        $user = User::where('email', $data['email'])->first();
        $user->paddle_id = $data['id'];
        $user->save();
    }

    private function handleCustomerUpdated(WebhookReceived $event): void
    {
        // user updated... but we don't need to track it
    }

    private function handleSubscriptionCreated(WebhookReceived $event): void
    {
//        $data = $event->payload['data'];
//        $user = User::where('paddle_id', $data['customer_id'])->first();
//        $user->newSubscription('default', $data['subscription_id'])->create();
    }
}
