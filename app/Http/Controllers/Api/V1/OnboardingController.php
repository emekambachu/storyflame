<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\UserResource;
use App\Services\AchievementService;
use App\Services\OnboardingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{

    public function index(OnboardingService $onboardingService,)
    {
        return $this->successResponse('success', [
            'question' => ChatMessageResource::make($onboardingService->getLastQuestion()),
            'questions_count' => $onboardingService->getQuestionCount(),
            'progress' => $onboardingService->getProgress()
        ]);
    }

    public function store(OnboardingService $onboardingService, Request $request)
    {
        $validated = $request->validate([
            'options' => ['nullable', 'array'],
            'message' => ['nullable', 'string'],
            'audio' => ['nullable', 'file', 'mimes:mp4,wav,webm'],
        ]);

        $onboardingService->addMessage(
            $validated['audio'] ?? null,
            $validated['message'] ?? null,
            $validated['options'] ?? null
        );
        $onboardingService->extractData();

        return $this->successResponse(
            'Success', [
            'message' => ChatMessageResource::make($onboardingService->getNextQuestion()),
            'progress' => $onboardingService->getProgress()
        ]);
    }

    /**
     * Clear the onboarding session and save the data to the user's profile
     * @return JsonResponse
     */
    public function summary(OnboardingService $onboardingService, AchievementService $achievementService)
    {
        $achievementService->updateProgress(auth()->user());
        return $this->successResponse('success', UserResource::make($onboardingService->getSummary()));
    }
}
