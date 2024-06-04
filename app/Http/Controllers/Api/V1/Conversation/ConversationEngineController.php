<?php

namespace App\Http\Controllers\Api\V1\Conversation;

use App\Engine\ConversationEngine;
use App\Engine\Storage\BaseStorage;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessageResource;
use App\Models\ChatMessage;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationEngineController extends Controller
{
    public function successConversationResponse($message, ConversationEngine $engine): JsonResponse
    {
        return $this->successResponse($message, [
            'identifier' => $engine->getIdentifier(),
            'question' => ChatMessageResource::make($engine->getLastQuestion()),
            'progress' => $engine->getProgress(),
            'data' => [
                'extracted' => $engine->getStorage()->getExtractedData(),
                'branches' => $engine->getStorage()->getBranches(),
                'history' => $engine->getStorage()->getHistory(),
            ],
        ]);
    }


    public function index(Request $request, string $engine): JsonResponse
    {
        $validated = $request->validate([
            'identifier' => ['nullable', 'string'],
        ]);

        // create new engine with session storage
        $engine = ConversationEngine::make($engine);

        if (isset($validated['identifier'])) {
            $engine = $engine->setStorage(
                BaseStorage::make($validated['identifier'])
            );
        } else {
            $engine = $engine->withCacheStorage();
        }

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

        $engine = ConversationEngine::make($engine)
            ->setStorage(
                BaseStorage::make($validated['identifier'])
            );

        $engine->process($validated['message']);

        return $this->successConversationResponse('Message sent', $engine);
    }
}
