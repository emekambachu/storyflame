<?php

use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(static function (){

    Route::get('/user/profile', [UserProfileController::class, 'profile']);
    Route::post('/user/bio/update', [UserProfileController::class, 'updateBio']);
    Route::post('/user/password/update', [UserProfileController::class, 'updatePassword']);
    Route::post('/user/avatar/update', [UserProfileController::class, 'updateAvatar']);

});
