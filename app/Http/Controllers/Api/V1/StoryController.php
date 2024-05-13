<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Services\StoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StoryController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Story::class);

        return auth()->user()->stories;
    }

    public function store(Request $request, StoryService $storyService)
    {
        Gate::authorize('create', Story::class);

        $validated = $request->validate([
           'file' => ['required', 'file', 'mimes:pdf'],
        ]);

        return $storyService->process($validated['file']);

//        $data = $request->validate([
//            'name' => ['required'],
//        ]);

//        return auth()->user()->stories()->create($data);
    }

    public function show(Story $story)
    {
        Gate::authorize('view', $story);

        return $story;
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
