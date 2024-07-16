<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\Subscription;
use App\Models\SubscriptionDowngrade;
use App\Models\SubscriptionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Paddle\Customer;

class SubscriptionController extends Controller
{
    private $user;
    public function index()
    {
        $user = Auth::user();

        $subscriptionInfo = $user->getActiveSubscriptionInfo();

        return response()->json([
            'subscription' => $subscriptionInfo,
            'plans' => $this->getPlans()
        ]);
    }

    private function getPlans()
    {
        // get products and product prices from the database where type="subscription" and status="active" ordered by products.order ASC
        $products = Product::with('productPrices')
            ->where('type', 'subscription')
            ->where('status', 'active')
            ->orderBy('order')
            ->get();

        Log::info('Products and ProductPrices are ' . $products->toJson());

        // return the productresource with productPrices
        return ProductResource::collection($products);

        return [
            [
                'name' => 'Premium',
                'prices' => [
                    [
                        'type' => 'monthly',
                        'price' => 22,
                    ],
                    [
                        'type' => 'annual',
                        'price' => 18,
                        'discount' => "Save $48"
                    ]
                ],
                'features' => [
                    'Unlimited guided development',
                    '5 Stories per month',
                    '1 Progress Assessment per month'
                ]
            ],
            [
                'name' => 'Pro',
                'prices' => [
                    [
                        'type' => 'monthly',
                        'price' => 55,
                    ],
                    [
                        'type' => 'annual',
                        'price' => 44,
                        'discount' => "Save $132"
                    ]
                ],
                'features' => [
                    'Unlimited guided development',
                    'Unlimited Stories per month',
                    '120 Progress Assessments'
                ]
            ]
        ];
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $plan = $request->input('plan');

        $subscription = $user->checkout($plan);

        return response()->json(['subscription' => $subscription], 201);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $newProductPricePaddleId = $request->input('productPricePaddleId');

        $newProductPrice = ProductPrice::with('product')
            ->where('paddle_id', $newProductPricePaddleId)
            ->first();

        if(!$newProductPrice){
            return response()->json(['error' => 'Product price not found'], 404);
        }

        /**
         * @var Subscription|null $subscription
         */
        $subscription = $user->getActiveSubscription();
        if (!$subscription) {
            return response()->json(['error' => 'No active subscription found'], 404);
        }

        $result = $subscription->updateSubscription($newProductPrice);
        return response()->json($result, $result['status']);
    }

    public function destroy($paddleId)
    {
        $user = Auth::user();
        $subscription = $user->subscriptions()->where('paddle_id', $paddleId)->first();

        try {
            $subscription->deleteSubscriptionDowngrades();

            $subscription->cancel(false);
            return response()->json(['subscription' => $subscription->fresh()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to cancel subscription: ' . $e->getMessage()], 500);
        }
    }

    public function destroyDowngrade($paddleId)
    {
        $user = Auth::user();
        /**
         * @var Subscription|null $subscription
         */
        $subscription = Subscription::where('paddle_id', $paddleId)->first();

        if(!$subscription) {
            return response()->json(['error' => 'Subscription not found'], 404);
        }

        if($subscription->billable->id !== $user->id) {
            return response()->json(['error' => 'You do not have permission to cancel this subscription'], 403);
        }

        try {
            $subscription->deleteSubscriptionDowngrades();

            return response()->json(['subscription' => $subscription->fresh()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to cancel subscription change: ' . $e->getMessage()], 500);
        }
    }

    public function invoices()
    {
        $user = Auth::user();
        $invoices = $user->transactions()->get();

        // laravel log with the user id and invoices
        Log::info('User ID: ' . $user->id . ' Invoices: ' . $invoices);

        return response()->json([
            'invoices' => $invoices
        ]);
    }

    // create a method to get the invoice PDF link using Cashier::api() for https://api.paddle.com/transactions/{transaction_id}
    public function invoiceLink($id)
    {
        $user = Auth::user();
        $transaction = $user->transactions()->where('paddle_id', $id)->first();

        $pdf = $transaction->invoicePdf();

        return response()->json(['url' => $pdf]);
    }

    public function createCustomer(Request $request)
    {
        $user = Auth::user();

        try {
            if(!$user->customer){
                $user = $user->createAsCustomer([
                    'email' => $user->email,
                    'name' => $user->name ?? 'User',
                ]);
            }

            return response()->json(['user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
