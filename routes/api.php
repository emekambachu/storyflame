<?php

use App\Http\Controllers\Api\V1\Authentication\LoginController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Membership\ReferralTypeController;
use App\Http\Controllers\User\Auth\UserRegistrationController;
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'App\Http\Controllers\Api',
], function () {
    Route::group([
        'prefix' => 'v1',
        'namespace' => 'V1',
    ], function () {
        Route::group([
            'prefix' => 'auth',
            'namespace' => 'Authentication',
        ], function () {
            Route::post('register', 'RegisterController@register');
            Route::post('login', [LoginController::class, 'authenticate']);
            Route::post('federate', 'LoginController@federate');
            Route::get('user', [
                \App\Http\Controllers\Api\V1\Authentication\LoginController::class, 'user'
            ]);
            Route::post('logout', 'LoginController@logout');
        });

        Route::group([
            'prefix' => 'onboarding',
        ], function () {
            Route::get('/', 'OnboardingController@index');
            Route::post('/', 'OnboardingController@store');
            Route::get('summary', 'OnboardingController@summary');
        });
        Route::post('transcribe', 'TranscriptionController@transcribe');

        Route::group([
            'prefix' => 'achievements',
        ], function () {
            Route::resource('achievements', 'AchievementController');
        });

        Route::resource('stories', 'StoryController');

        Route::group([
            'prefix' => 'conversation',
            'namespace' => 'Conversation',
        ], function () {
            Route::get('{engine}', 'ConversationEngineController@index');
            Route::post('{engine}', 'ConversationEngineController@store');
        });
    });
});

Route::middleware('auth:sanctum')->group(static function () {
    Route::prefix('v1/subscriptions')->group(function () {
        Route::get('/', [
            \App\Http\Controllers\Api\SubscriptionController::class,
            'index'
        ]);
        Route::post('/', [
            \App\Http\Controllers\Api\SubscriptionController::class,
            'store'
        ]);
        Route::post('/customer', [
            \App\Http\Controllers\Api\SubscriptionController::class,
            'createCustomer'
        ]);
        Route::put('/{id}', [
            \App\Http\Controllers\Api\SubscriptionController::class,
            'update'
        ]);
        Route::delete('/{paddleId}', [
            \App\Http\Controllers\Api\SubscriptionController::class,
            'destroy'
        ]);
        Route::delete('/{paddleId}/change', [
            \App\Http\Controllers\Api\SubscriptionController::class,
            'destroyDowngrade'
        ]);
        Route::get('/invoices', [
            \App\Http\Controllers\Api\SubscriptionController::class,
            'invoices'
        ]);
        Route::get('/invoices/{id}', [
            \App\Http\Controllers\Api\SubscriptionController::class,
            'invoiceLink'
        ]);
    });

    Route::middleware('auth:sanctum')->group(static function () {
        Route::prefix('v1/subscriptions')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\SubscriptionController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\SubscriptionController::class, 'store']);
            Route::post('/customer', [\App\Http\Controllers\Api\SubscriptionController::class, 'createCustomer']);
            Route::put('/{id}', [\App\Http\Controllers\Api\SubscriptionController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\SubscriptionController::class, 'destroy']);
            Route::get('/invoices', [\App\Http\Controllers\Api\SubscriptionController::class, 'invoices']);
            Route::get('/invoices/{id}', [\App\Http\Controllers\Api\SubscriptionController::class, 'invoiceLink']);
        });
    });

});

# fallback route
Route::fallback(function () {
    return response()->json([
        'message' => 'Not Found',
        'type' => 'error',
        'details' => 'The requested resource was not found.'
    ], 404);
});

// Categories
Route::get('/categories', [
    CategoryController::class,
    'index'
]);
Route::post('/categories', [
    CategoryController::class,
    'store'
]);
Route::put('/categories', [
    CategoryController::class,
    'update'
]);
Route::delete('/categories', [
    CategoryController::class,
    'destroy'
]);

Route::get('/referral-types', [ReferralTypeController::class, 'index']);

Route::post('/sign-on', [UserRegistrationController::class, 'signOn']);
Route::post('/sign-on/verify', [UserRegistrationController::class, 'verify']);


include __DIR__ . '/admin/api.php';
include __DIR__ . '/user/api.php';
