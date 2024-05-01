<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\OnboardingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OnboardingController extends Controller
{

    public function index(OnboardingService $onboardingService,)
    {
        return $this->successResponse('success', [
            'questionsCount' => $onboardingService->getQuestionCount()
        ]);
    }

    public function store(OnboardingService $onboardingService, Request $request)
    {
        $validated = $request->validate([
            'messages' => ['required', 'array', 'min:1'],
            'messages.*.type' => ['required', 'string', 'in:answer,question'],
            'messages.*.text' => ['required', 'string'],
            'data' => ['array']
        ]);

        $latestMessages = array_slice($validated['messages'], -2);
        $extracted = $onboardingService->extractData($latestMessages, $validated['data']);
        Log::info('Extracted data', $extracted);

        # merge extracted data with existing data
        $data = array_merge($validated['data'], $extracted);

        $nextQuestion = $onboardingService->generateNextQuestion($validated['messages'], $data);

        return $this->successResponse(
            'Success', [
            'message' => $nextQuestion ? [
                'type' => 'question',
                'text' => $nextQuestion,
            ] : [
                'type' => 'finish',
                'text' => 'Thank you for your time. We have all the information we need.'
            ],
            'user' => $data,
        ]);
    }
}
