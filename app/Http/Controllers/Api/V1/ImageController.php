<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Jobs\GenerateImageJob;
use App\Models\AIImageSlot;
use App\Models\Image;
use App\Models\ImageType;
use App\Services\ImageGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    protected $imageGenerationService;

    public function __construct(ImageGenerationService $imageGenerationService)
    {
        $this->imageGenerationService = $imageGenerationService;
    }

    public function getImageTypes($model, $id)
    {
        // Implementation depends on how you've set up your ImageTypeSchema
        // This is a placeholder
        $modelClass = "App\\Models\\" . ucfirst($model);
        $modelInstance = $modelClass::findOrFail($id);

        return response()->json($modelInstance->imageTypes());
    }

    public function getImages($model, $id)
    {
        $modelClass = "App\\Models\\" . ucfirst($model);
        $modelInstance = $modelClass::findOrFail($id);

        return response()->json(
            ImageResource::collection($modelInstance->images)
        );
    }

    public function store(Request $request, $model, $id, $imageTypeSlug)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $modelClass = "App\\Models\\" . ucfirst($model);
        $modelInstance = $modelClass::findOrFail($id);
        $imageType = ImageType::where('slug', $imageTypeSlug)->firstOrFail();

        $image = $modelInstance->images()->create([
            'image_type_id' => $imageType->id,
            'path' => $request->file('image')->store('images', 'public'),
        ]);

        return response()->json(['message' => 'Image uploaded successfully', 'image' => $image]);
    }

    public function generate(Request $request, $model, $id, $imageTypeSlug)
    {
        $modelClass = "App\\Models\\" . ucfirst($model);
        $modelInstance = $modelClass::findOrFail($id);
        $imageType = ImageType::where('slug', $imageTypeSlug)->firstOrFail();

        $user = auth()->user();
        $availableSlot = $user->aiImageSlots()
            ->where('slot_status', AIImageSlot::STATUS_AVAILABLE)
            ->where('expires_at', '>', now())
            ->first();

        if (!$availableSlot) {
            return response()->json(['message' => 'No available image slots'], 403);
        }

        if($missingDataPoints = $imageType->getMissingRequirements($modelInstance, $id)) {
            return response()->json(['message' => 'Missing data points', 'data' => ['missing_data_points' => $missingDataPoints]], 422);
        }

        // Need to check the imageType for get requirements... if you don't have data for them, then you can't generate the image
        // return an error with the necessary data points that need to be filled out

        $prompt = $this->imageGenerationService->generatePrompt($modelInstance, $imageType, $request->all());

        $image = $modelInstance->images()->create([
            'image_type_id' => $imageType->id,
            'generation_service_name' => 'leonardo',
        ]);

        $availableSlot->update([
            'slot_status' => AIImageSlot::STATUS_PROCESSING,
            'image_id' => $image->id,
        ]);

        GenerateImageJob::dispatch($image, $prompt, $imageType->prompt_settings);

        return response()->json(['message' => 'Image generation started', 'image' => $image]);
    }

    public function getRequirements($model, $id, $imageTypeSlug)
    {
        $modelClass = "App\\Models\\" . ucfirst($model);
        $modelInstance = $modelClass::findOrFail($id);

        /* @var ImageType $imageType */
        $imageType = ImageType::where('slug', $imageTypeSlug)->firstOrFail();

        return response()->json($imageType->getMissingRequirements($modelInstance, $id));
    }
}
