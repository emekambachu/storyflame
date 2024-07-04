<?php

namespace App\Http\Controllers\Admin\LlmPrompt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LlmPrompt\AdminLlmPromptStoreRequest;
use App\Http\Requests\Admin\LlmPrompt\AdminLlmPromptUpdateRequest;
use App\Http\Resources\Admin\LlmPrompt\AdminLlmPromptResource;
use App\Services\Base\BaseService;
use App\Services\LlmPrompt\LlmPromptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminLlmPromptController extends Controller
{
    protected LlmPromptService $prompt;
    public function __construct(LlmPromptService $prompt){
        $this->prompt = $prompt;
    }

    public function index(): JsonResponse
    {
        try {
            $data = $this->prompt->llm_prompt()->with(
                'versions',
                'current_version',
                'updated_by'
            )->latest()->get();

            return response()->json([
                'success' => true,
                'prompts' => AdminLlmPromptResource::collection($data),
                'total' => $data->count()
            ]);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function store(AdminLlmPromptStoreRequest $request): JsonResponse
    {
        try {
            $data = $this->prompt->storeLlmPrompt($request);
            return response()->json($data);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function update(AdminLlmPromptUpdateRequest $request, $slug): JsonResponse
    {
        try {
            $data = $this->prompt->updateLlmPrompt($request, $slug);
            return response()->json($data);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function currentVersion($promptSlug, $versionId){
        try {
            $data = $this->prompt->assignCurrentPromptVersion($promptSlug, $versionId);
            return response()->json($data);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function destroy($slug): JsonResponse
    {
        try {
            $data = $this->prompt->deleteLlmPrompt($slug);
            return response()->json($data);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }


}
