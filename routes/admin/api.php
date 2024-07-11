<?php

use App\Http\Controllers\Admin\Achievement\AdminAchievementController;
use App\Http\Controllers\Admin\AdminReferralTypeController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DataPoint\AdminDataPointController;
use App\Http\Controllers\Admin\LlmPrompt\AdminLlmPromptController;
use App\Http\Controllers\Admin\Summary\AdminSummaryController;
use Illuminate\Support\Facades\Route;

Route::post('/admin/login', [AdminLoginController::class, 'login']);

// Custom sanctum admin guard authentication for Learning Portal
Route::middleware('auth:sanctum')->group(static function (){

    // Authentication
    Route::get('/admin/authenticate', [AdminLoginController::class, 'authenticate']);

    // Achievements
    Route::get('/admin/achievements', [AdminAchievementController::class, 'index']);
    // return only id and name
    Route::get('/admin/achievements/min', [AdminAchievementController::class, 'indexMin']);
    Route::get('/admin/achievement/{item_id}/categories', [AdminAchievementController::class, 'achievementCategories']);
    Route::post('/admin/achievements/store', [AdminAchievementController::class, 'store']);

    Route::middleware(['admin-role:super-admin'])->group(function () {
        Route::post('/admin/achievements/{item_id}/update', [AdminAchievementController::class, 'update']);
        Route::delete('/admin/achievements/{item_id}/delete', [AdminAchievementController::class, 'destroy']);
    });

    // Data Points
    Route::get('/admin/data-points', [AdminDataPointController::class, 'index']);
    // return only id and name
    Route::get('/admin/data-points/min', [AdminDataPointController::class, 'indexMin']);
    Route::get('/admin/data-points/{item_id}/categories', [AdminDataPointController::class, 'achievementCategories']);
    Route::post('/admin/data-points/store', [AdminDataPointController::class, 'store']);
    Route::post('/admin/data-points/{item_id}/update', [AdminDataPointController::class, 'update']);
    Route::delete('/admin/data-points/{item_id}/delete', [AdminDataPointController::class, 'destroy']);

    // Summaries
    Route::get('/admin/summaries', [AdminSummaryController::class, 'index']);
    // return only id and name
    Route::get('/admin/summaries/min', [AdminSummaryController::class, 'indexMin']);
    Route::get('/admin/summaries/{item_id}/categories', [AdminSummaryController::class, 'achievementCategories']);
    Route::post('/admin/summaries/store', [AdminSummaryController::class, 'store']);
    Route::post('/admin/summaries/{item_id}/update', [AdminSummaryController::class, 'update']);
    Route::delete('/admin/summaries/{item_id}/delete', [AdminSummaryController::class, 'destroy']);

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
