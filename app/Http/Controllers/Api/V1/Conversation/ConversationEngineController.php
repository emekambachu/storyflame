<?php

namespace App\Http\Controllers\Api\V1\Conversation;

use App\Engine\ConversationEngineFactory;
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
            'question' => ChatMessageResource::make($engine->getPreviousQuestion(true)),
            'progress' => $engine->getProgress(),
            'data' => $engine->getModel(),
//            'data' => [
//                'extracted' => $engine->getStorage()->getExtractedData(),
//                'branches' => $engine->getStorage()->getBranches(),
//                'history' => $engine->getStorage()->getHistory(),
//            ],
        ]);
    }

    // Need to start it knowing the imageType they want to create for the Model and Model ID they want to create

    public function startImageCreationConversation(Request $request, string $engine): JsonResponse
    {
        $validated = $request->validate([
            'identifier' => ['nullable', 'numeric'],
            'model' => ['required', 'string'],
            'model_id' => ['required', 'numeric'],
            'image_type_slug' => ['required', 'string'],
        ]);

        $engine = ConversationEngineFactory::makeFromIdentifier($engine, $request->get('identifier'));


        $engine->process('start', [
            'model' => $validated['model'],
            'model_id' => $validated['model_id'],
            'image_type_slug' => $validated['image_type_slug'],
        ]);

        return $this->successConversationResponse('New Conversation started', $engine);
    }

    public function index(Request $request, string $engine): JsonResponse
    {
        $validated = $request->validate([
            'identifier' => ['nullable', 'numeric'],
            'achievement_id' => ['nullable']
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
