<?php

use App\Http\Controllers\Admin\AdminAchievementController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::post('/admin/login', [AdminLoginController::class, 'login']);

// Custom sanctum admin guard authentication for Learning Portal
Route::middleware('auth:admin-api')->group(static function (){

    Route::get('/admin/authenticate', [AdminLoginController::class, 'authenticate']);

    // Achievements
    Route::get('/admin/achievements', [AdminAchievementController::class, 'index']);
    Route::get('/admin/achievement/{item_id}/categories', [AdminAchievementController::class, 'achievementCategories']);
    Route::post('/admin/achievements/store', [AdminAchievementController::class, 'store']);
    Route::post('/admin/achievements/update', [AdminAchievementController::class, 'update']);
    Route::delete('/admin/achievements/delete', [AdminAchievementController::class, 'destroy']);


    // Learning Logout
    Route::get('/admin/logout', [AdminLoginController::class, 'logout']);
});
