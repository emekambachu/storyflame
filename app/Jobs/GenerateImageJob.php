<?php

namespace App\Jobs;

use App\Models\Image;
use App\Services\ImageGenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image;
    protected $prompt;
    protected $imageTypePromptSettings;

    public function __construct(Image $image, string $prompt, array $imageTypePromptSettings)
    {
        $this->image = $image;
        $this->prompt = $prompt;
        $this->imageTypePromptSettings = $imageTypePromptSettings;
    }

    public function handle(ImageGenerationService $imageGenerationService)
    {
        Log::info("Starting image generation", ['image_id' => $this->image->id]);

        try {
            $generationResponse = $imageGenerationService->generateLeonardoImage($this->prompt, $this->imageTypePromptSettings);

            $this->image->update([
                'generation_id' => $generationResponse['sdGenerationJob']['generationId'],
                'token_cost' => $generationResponse['sdGenerationJob']['apiCreditCost'],
            ]);

            Log::info("Image generation job submitted", [
                'image_id' => $this->image->id,
                'generation_id' => $generationResponse['sdGenerationJob']['generationId'],
            ]);
        } catch (\Exception $e) {
            Log::error("Error generating image", [
                'image_id' => $this->image->id,
                'error' => $e->getMessage(),
            ]);

            $this->image->update([
                'generation_service_name' => null,
                'generation_id' => null,
                'generation_settings' => null,
            ]);

            throw $e;
        }
    }
}
