<?php

use Illuminate\Support\Facades\Route;

# direct all request to app.blade except /api and /public
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api|admin).*$');

Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::get('/admin/{any}', function () {
    return view('admin.index');
})->where('any', '^(?!api|login).*$');
