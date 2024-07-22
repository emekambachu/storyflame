<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\StoryResource;
use App\Models\Story;
use App\Services\StoryCreatingService;
use App\Services\StoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StoryController extends Controller
{
    public function __construct(
        private readonly StoryCreatingService $creatingService
    )
    {
    }

    public function index()
    {
        Gate::authorize('viewAny', Story::class);

        return $this->successResponse('success', StoryResource::collection(auth()->user()->stories));
    }

    public function store(Request $request, StoryService $storyService)
    {
        Gate::authorize('create', Story::class);

        $validated = $request->validate([
            'file' => ['nullable', 'file', 'mimes:pdf'],
        ]);

        return $this->successResponse('success', [
            'question' => ChatMessageResource::make(
                $this->creatingService->getPreviousQuestion(auth()->user())
            ),
            'progress' => $this->creatingService->getProgress(auth()->user())
        ]);

//        return $storyService->process($validated['file']);

//        $data = $request->validate([
//            'name' => ['required'],
//        ]);

//        return auth()->user()->stories()->create($data);
    }

    public function show(Story $story)
    {
        Gate::authorize('view', $story);

        return $this->successResponse('success', StoryResource::make($story));
    }

    public function update(Request $request, Story $story)
    {
        Gate::authorize('update', $story);

        $data = $request->validate([
            'name' => ['required'],
        ]);

        $story->update($data);

        return $story;
    }

    public function destroy(Story $story)
    {
        Gate::authorize('delete', $story);

        $story->delete();

        return response()->json();
    }
}
