<?php

namespace App\Http\Controllers;

use App\Services\FreeTrialService;
use Illuminate\Http\Request;

class FreeTrialController extends Controller
{
    protected $freeTrialService;

    public function __construct(FreeTrialService $freeTrialService)
    {
        $this->freeTrialService = $freeTrialService;
    }

    public function getRemainingInteractions(Request $request)
    {
        $user = $request->user();
        $totalRemaining = $this->freeTrialService->getRemainingInteractions($user);
        $dailyRemaining = $this->freeTrialService->getRemainingInteractionsForToday($user);
        $imageGenerationsRemaining = $this->freeTrialService->getRemainingImageGeneration($user);
        return response()->json([
            'total_remaining_interactions' => $totalRemaining,
            'daily_remaining_interactions' => $dailyRemaining,
            'image_generations_remaining' => $imageGenerationsRemaining,
        ]);
    }

    public function trackInteraction(Request $request)
    {
        $success = $this->freeTrialService->trackInteraction($request->user());
        return response()->json(['success' => $success]);
    }

    public function trackImageGeneration(Request $request)
    {
        $success = $this->freeTrialService->trackImageGeneration($request->user());
        return response()->json(['success' => $success]);
    }
}
