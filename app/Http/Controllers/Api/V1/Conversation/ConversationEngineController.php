<?php

namespace App\Http\Controllers\Api\V1\Conversation;

use App\Engine\ConversationEngineV2;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessageResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationEngineController extends Controller
{
    public function successConversationResponse($message, ConversationEngineV2 $engine): JsonResponse
    {
        return $this->successResponse($message, [
            'identifier' => $engine->getIdentifier(),
            'endpoint' => $engine->getEndpoint(),
            'question' => ChatMessageResource::make($engine->getLastQuestion()),
//            'progress' => $engine->getProgress(),
//            'data' => [
//                'extracted' => $engine->getStorage()->getExtractedData(),
//                'branches' => $engine->getStorage()->getBranches(),
//                'history' => $engine->getStorage()->getHistory(),
//            ],
        ]);
    }


    public function index(Request $request, string $engine): JsonResponse
    {
        $validated = $request->validate([
            'identifier' => ['nullable', 'string'],
        ]);

        // create new engine with session storage
        $engine = ConversationEngineV2::makeFromIdentifier(
            $engine,
            $validated['identifier'] ?? null
        );

        return $this->successConversationResponse('New Conversation started', $engine);
    }

    /**
     * Send message to the conversation engine
     * @param Request $request
     * @param $engine
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request, $engine)
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string'],
            'message' => ['nullable', 'string'],
            'choice_options' => ['nullable', 'array'],
            'choice_options.*' => ['string'],
            'audio' => ['nullable', 'file', 'mimes:mp4,wav,webm'],
        ]);

        $engine = ConversationEngineV2::makeFromIdentifier($engine, $validated['identifier']);

        $engine->process($validated['message']);

        return $this->successConversationResponse('Message sent', $engine);
    }
}
