<?php

use Illuminate\Support\Facades\Route;

# direct all request to app.blade
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
