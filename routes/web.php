<?php

use Illuminate\Support\Facades\Route;

# direct all request to app.blade except /api and /public
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');
