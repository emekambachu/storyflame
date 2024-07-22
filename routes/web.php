<?php

use Illuminate\Support\Facades\Route;

# direct all request to app.blade except /api and /public
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api|admin).*$');

Route::post('/paddle/webhook', [\App\Http\Controllers\Webhook\PaddleWebhookController::class, '__invoke']);
Route::post('/leonardo/webhook', [\App\Http\Controllers\Webhook\LeonardoWebhookController::class, '__invoke']);

Route::get('/test-env', function () {
    dd(config('cashier.webhook_secret'));
});

include __DIR__ . '/admin/web.php';
