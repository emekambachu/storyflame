<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        // get all user->subscriptions where the ends_at is in the future, sorted by ends_at asc
        $subscriptions = $user->activeSubscriptions();

        // if empty set subscriptions to the getPlans 'free' plan
        if ($subscriptions->isEmpty()) {
            $subscriptions = [
                [
                    'name' => 'Free Trial',
                    'price' => 0,
                    'features' => [
                        '30-minutes of guided development',
                        '1 Story per month',
                        '0 Generated Reports'
                    ]
                ]
            ];
        }

        return response()->json([
            'subscriptions' => $subscriptions,
            'plans' => $this->getPlans()
        ]);
    }

    private function getPlans()
    {
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
        $user = Auth::user();
        $user = User::find(38);
        $subscription = $user->subscriptions()->findOrFail($id);

        $plan = $request->input('plan');

        $subscription->swap($plan);

        return response()->json(['subscription' => $subscription]);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $user = User::find(38);
        $subscription = $user->subscriptions()->findOrFail($id);

        $subscription->cancel();

        return response()->json(null, 204);
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

    public function createCustomer(Request $request)
    {
        $user = Auth::user();
        $user = User::find(38);

        try {
            $user = $user->createAsCustomer([
                'email' => $user->email,
                'name' => $user->name,
            ]);

            return response()->json(['user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
