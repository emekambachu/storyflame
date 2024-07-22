<?php

namespace App\Http\Controllers\Api\V1;

use App\Engine\OnboardingConversationEngine;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\UserResource;
use App\Services\OnboardingService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{

    /**
     * @throws RequestException
     */
    public function index(OnboardingService $onboardingService,)
    {
        $user = auth()->user();
        return $this->successResponse('success', [
            'question' => ChatMessageResource::make($onboardingService->getPreviousQuestion($user)),
            'questions_count' => $onboardingService->getQuestionCount(),
            'progress' => $onboardingService->getProgress($user)
        ]);
    }

    /**
     * @throws RequestException
     */
    public function store(OnboardingService $onboardingService, Request $request)
    {
        $validated = $request->validate([
            'options' => ['nullable', 'array'],
            'message' => ['nullable', 'string'],
            'audio' => ['nullable', 'file', 'mimes:mp4,wav,webm'],
        ]);
        $user = auth()->user();
        $onboardingService->addMessage(
            $validated['audio'] ?? null,
            $validated['message'] ?? null,
            $validated['options'] ?? null
        );
        $onboardingService->extractData($user);

        return $this->successResponse(
            'Success', [
            'message' => ChatMessageResource::make($onboardingService->getNextQuestion()),
            'progress' => $onboardingService->getProgress($user)
        ]);
    }

    /**
     * Clear the onboarding session and save the data to the user's profile
     * @return JsonResponse
     */
    public function summary()
    {
        $user = auth()->user();
        OnboardingConversationEngine::generateBio($user);
        $user->extra_attributes->set('onboarded', true);
        $user->save();
        return $this->successResponse('success', UserResource::make(auth()->user()));
    }
}
