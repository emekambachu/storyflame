<?php

namespace App\Services\LlmPrompt;

use App\Http\Resources\Admin\LlmPrompt\AdminLlmPromptResource;
use App\Models\LlmPrompt\LlmPrompt;
use App\Models\LlmPrompt\LlmPromptVersion;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LlmPromptService
{
    public function llm_prompt(): LlmPrompt
    {
        return new LlmPrompt();
    }

    public function llmPromptVersions(): LlmPromptVersion
    {
        return new LlmPromptVersion();
    }

    public function storeLlmPrompt($request): array
    {
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            $inputs['updated_by_id'] = Auth::id();
            $inputs['slug'] = Str::slug($inputs['name']).BaseService::randomCharacters(10, '0123456789ABCDEFGH');

            $prompt = $this->llm_prompt()->create($inputs);

            $promptVersion = $this->llmPromptVersions()->create([
                'llm_prompt_id' => $prompt->id,
                'name' => $inputs['name'],
                'prompt_value' => $inputs['prompt_value'],
                'updated_by_id' => Auth::id(),
                'slug' => $prompt->slug
            ]);

            // assign recently created as current version
            $prompt->current_prompt_version_id = $promptVersion->id;
            $prompt->save();

            DB::commit();
            return [
                'success' => true,
                'llm_prompt' => new AdminLlmPromptResource($promptVersion)
            ];

        }catch (\Exception $e){
            DB::rollBack();
            BaseService::logError($e);
            return [
                'success' => false,
                'error_message' => 'Something went wrong',
                'server_error' => $e->getMessage(),
                'status_code' => 500
            ];
        }
    }

    public function updateLlmPrompt($request, $slug): array
    {
        $prompt = $this->llm_prompt()->where('slug', $slug)->first();

        DB::beginTransaction();
        try {
            $inputs = $request->all();

            $inputs['updated_by_user_id'] = Auth::id();
            $this->llm_prompt()->update($inputs);

            $promptVersion = $this->llmPromptVersions()->create([
                'llm_prompt_id' => $prompt->id,
                'name' => $inputs['name'],
                'prompt_value' => $inputs['prompt_value'],
                'updated_by_user_id' => Auth::id(),
                'slug' => $prompt->slug
            ]);

            // assign recent update as current version
            $prompt->current_prompt_version_id = $promptVersion->id;
            $prompt->save();

            DB::commit();
            return [
                'success' => true,
                'llm_prompt' => new AdminLlmPromptResource($promptVersion)
            ];

        }catch (\Exception $e){
            DB::rollBack();
            BaseService::logError($e);
            return [
                'success' => false,
                'error_message' => 'Something went wrong',
                'server_error' => $e->getMessage(),
                'status_code' => 500
            ];
        }
    }

    public function deleteLlmPrompt($slug): array
    {
        // Start a transaction
        DB::beginTransaction();
        try {
            $prompt = $this->llm_prompt()->where('slug', $slug)->first();
            // Check if the achievement exists
            if (!$prompt) {
                return [
                    'success' => false,
                    'error_message' => 'Prompt Not Found'
                ];
            }

            // Detach the categories
            if ($prompt->cversions && $prompt->versions->count() > 0) {
                $prompt->versions()->delete();
            }

            // Delete the achievement
            $prompt->delete();

            // Commit the transaction
            DB::commit();

            return [
                'success' => true,
                'message' => 'Prompt Deleted Successfully'
            ];

        } catch (\Exception $e) {
            // Rollback the transaction in case of errors
            DB::rollBack();
            // Log the error
            BaseService::logError($e);

            return [
                'success' => false,
                'error_message' => 'An error occurred while deleting'
            ];
        }
    }

    public function assignCurrentPromptVersion($id){

        $promptVersion = $this->llmPromptVersions()
            ->with('llmPrompt:id,slug')
            ->where('id', $id)->first();

        if (!$promptVersion || !$promptVersion->llmPrompt) {
            return [
                'success' => false,
                'error_message' => 'Prompt Not Found'
            ];
        }

        $prompt = $this->llm_prompt()->where('id', $promptVersion->llmPrompt->id)->first();
        $prompt->current_prompt_version_id = $promptVersion->id;
        $prompt->save();

        return [
            'success' => true,
            'message' => 'Current Prompt Version Assigned Successfully'
        ];

    }

}
