<?php

use Illuminate\Support\Facades\Route;

# direct all request to app.blade except /api and /public
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api|admin).*$');

Route::post('/paddle/webhook', [\App\Http\Controllers\WebhookController::class, '__invoke']);

include __DIR__ . '/admin/web.php';
