<?php

use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->group(static function (){

    Route::get('/profile/update', [UserProfileController::class, 'updateProfile']);

});
