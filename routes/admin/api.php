<?php

use App\Http\Controllers\Admin\Achievement\AdminAchievementController;
use App\Http\Controllers\Admin\AdminReferralTypeController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DataPoint\AdminDataPointController;
use App\Http\Controllers\Admin\LlmPrompt\AdminLlmPromptController;
use App\Http\Controllers\Admin\Summary\AdminSummaryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    // Login route
    Route::post('/login', [AdminLoginController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        // Authentication
        Route::get('/authenticate', [
            AdminLoginController::class,
            'authenticate'
        ]);
        Route::post('/logout', [
            AdminLoginController::class,
            'logout'
        ]);

        // Achievements
        Route::get('/achievements', [
            AdminAchievementController::class,
            'index'
        ]);
        Route::get('/achievements/min', [
            AdminAchievementController::class,
            'indexMin'
        ]);
        Route::get('/achievement/{item_id}/categories', [
            AdminAchievementController::class,
            'achievementCategories'
        ]);
        Route::post('/achievements/store', [
            AdminAchievementController::class,
            'store'
        ]);

        Route::middleware('admin-role:super-admin')->group(function () {
            Route::post('/achievements/{item_id}/update', [
                AdminAchievementController::class,
                'update'
            ]);
            Route::delete('/achievements/{item_id}/delete', [
                AdminAchievementController::class,
                'destroy'
            ]);
        });

        // Data Points
        Route::get('/data-points', [
            AdminDataPointController::class,
            'index'
        ]);
        Route::get('/data-points/min', [
            AdminDataPointController::class,
            'indexMin'
        ]);
        Route::get('/data-points/{item_id}/categories', [
            AdminDataPointController::class,
            'achievementCategories'
        ]);
        Route::post('/data-points/store', [
            AdminDataPointController::class,
            'store'
        ]);
        Route::post('/data-points/{item_id}/update', [
            AdminDataPointController::class,
            'update'
        ]);
        Route::delete('/data-points/{item_id}/delete', [
            AdminDataPointController::class,
            'destroy'
        ]);

        // Image Types
        Route::apiResource('image-types', \App\Http\Controllers\Admin\ImageTypeController::class);

        // Summaries
        Route::get('/summaries', [
            AdminSummaryController::class,
            'index'
        ]);
        Route::get('/summaries/min', [
            AdminSummaryController::class,
            'indexMin'
        ]);
        Route::get('/summaries/{item_id}/categories', [
            AdminSummaryController::class,
            'achievementCategories'
        ]);
        Route::post('/summaries/store', [
            AdminSummaryController::class,
            'store'
        ]);
        Route::post('/summaries/{item_id}/update', [
            AdminSummaryController::class,
            'update'
        ]);
        Route::delete('/summaries/{item_id}/delete', [
            AdminSummaryController::class,
            'destroy'
        ]);

    // LLM Prompts
    Route::get('/admin/llm-prompts', [AdminLlmPromptController::class, 'index']);
    Route::post('/admin/llm-prompts/store', [AdminLlmPromptController::class, 'store']);
    Route::post('/admin/llm-prompts/version/{slug}', [AdminLlmPromptController::class, 'version']);
    Route::post('/admin/llm-prompts/version/{id}/current', [AdminLlmPromptController::class, 'currentVersion']);
    Route::post('/admin/llm-prompts/{slug}/update', [AdminLlmPromptController::class, 'update']);
    Route::delete('/admin/llm-prompts/{slug}/delete', [AdminLlmPromptController::class, 'destroy']);

    // Referral Types
    Route::get('/admin/referral-types', [AdminReferralTypeController::class, 'index']);
    Route::post('/admin/referral-types/store', [AdminReferralTypeController::class, 'store']);
    Route::post('/admin/referral-types/{slug}/update', [AdminReferralTypeController::class, 'update']);
    Route::delete('/admin/referral-types/{slug}/delete', [AdminReferralTypeController::class, 'destroy']);

    // Logout
    Route::post('/admin/logout', [AdminLoginController::class, 'logout']);

    });
});
