<?php

use App\Http\Controllers\Admin\Achievement\AdminAchievementController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DataPoint\AdminDataPointController;
use Illuminate\Support\Facades\Route;

Route::post('/admin/login', [AdminLoginController::class, 'login']);

// Custom sanctum admin guard authentication for Learning Portal
Route::middleware('auth:admin-api')->group(static function (){

    // Authentication
    Route::get('/admin/authenticate', [AdminLoginController::class, 'authenticate']);

    // Achievements
    Route::get('/admin/achievements', [AdminAchievementController::class, 'index']);
    Route::get('/admin/achievement/{item_id}/categories', [AdminAchievementController::class, 'achievementCategories']);
    Route::post('/admin/achievements/store', [AdminAchievementController::class, 'store']);
    Route::post('/admin/achievements/update', [AdminAchievementController::class, 'update']);
    Route::delete('/admin/achievements/delete', [AdminAchievementController::class, 'destroy']);

    // Data Points
    Route::get('/admin/data-points', [AdminDataPointController::class, 'index']);
    Route::get('/admin/data-points/{item_id}/categories', [AdminDataPointController::class, 'achievementCategories']);
    Route::post('/admin/data-points/store', [AdminDataPointController::class, 'store']);
    Route::put('/admin/data-points/update', [AdminDataPointController::class, 'update']);
    Route::delete('/admin/data-points/delete', [AdminDataPointController::class, 'destroy']);

    // Summaries
    Route::get('/admin/summaries', [AdminAchievementController::class, 'index']);
    Route::get('/admin/summaries/{item_id}/categories', [AdminAchievementController::class, 'achievementCategories']);
    Route::post('/admin/summaries/store', [AdminAchievementController::class, 'store']);
    Route::post('/admin/summaries/update', [AdminAchievementController::class, 'update']);
    Route::delete('/admin/summaries/delete', [AdminAchievementController::class, 'destroy']);

    // Logout
    Route::get('/admin/logout', [AdminLoginController::class, 'logout']);
});
