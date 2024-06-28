<?php

use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->group(static function (){

    Route::get('/user/profile', [UserProfileController::class, 'profile']);
    Route::post('/user/profile/update', [UserProfileController::class, 'updateProfile']);

});
