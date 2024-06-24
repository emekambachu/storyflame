<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::get('/admin/{any}', function () {
    return view('admin.index');
})->where('any', '^(?!api|login).*$');
