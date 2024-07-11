<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductPrice;
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
        $user = User::find(38);

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
        $user = User::find(38);
        $plan = $request->input('plan');

        $subscription = $user->checkout($plan);

//        $subscription = $user->newSubscription('default', $plan)->create();

        return response()->json(['subscription' => $subscription], 201);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $user = User::find(38);

        $newProductPricePaddleId = $request->input('productPricePaddleId');

        $productPrice = ProductPrice::with('product')
            ->where('paddle_id', $newProductPricePaddleId)
            ->first();

        if(!$productPrice){
            return response()->json(['error' => 'Product price not found'], 404);
        }

        $subscription = $user->getActiveSubscription();
        if (!$subscription) {
            return response()->json(['error' => 'No active subscription found'], 404);
        }

        $subscriptionItem = $user->getActiveSubscriptionItem();
        $isSameProduct = $subscriptionItem->product_id === $productPrice->product->paddle_id;
        $isSameProductPrice = $subscriptionItem->price_id === $newProductPricePaddleId;

        // if same product and same price, return
        if ($isSameProduct && $isSameProductPrice) {
            return response()->json(['subscription' => $subscription->fresh()]);
        }

        // By default, prorate immediately
        $subscription->prorateImmediately();

        Log::info('Swapping subscription to new ProductPrice.paddle_id: ' . $newProductPricePaddleId);

        try {
            $subscription->swap($newProductPricePaddleId);
            return response()->json(['subscription' => $subscription->fresh()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update subscription: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $user = User::find(38);
        $subscription = $user->subscriptions()->findOrFail($id);

        $subscription->cancel();

        try {
            $subscription->cancel();
            return response()->json(['subscription' => $subscription->fresh()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to cancel subscription: ' . $e->getMessage()], 500);
        }
    }

    public function invoices()
    {
        $user = Auth::user();
        $user = User::find(38);
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
        $user = User::find(38);
        $transaction = $user->transactions()->where('paddle_id', $id)->first();

        $pdf = $transaction->invoicePdf();

        return response()->json(['url' => $pdf]);
    }

    public function createCustomer(Request $request)
    {
        $user = Auth::user();
        $user = User::find(38);

        try {
            if(!$user->customer){
                $user = $user->createAsCustomer([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);
            }

            return response()->json(['user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
