<?php

use App\Http\Controllers\Category\CategoryController;
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
            Route::post('login', 'LoginController@authenticate');
            Route::post('federate', 'LoginController@federate');
            Route::middleware('auth:sanctum')->group(function () {
                Route::get('user', 'LoginController@user');
                Route::post('logout', 'LoginController@logout');
            });
        });

        Route::middleware('auth:sanctum')->group(function () {
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
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories', [CategoryController::class, 'update']);
Route::delete('/categories', [CategoryController::class, 'destroy']);

include __DIR__ . '/admin/api.php';
include __DIR__ . '/user/api.php';
