<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Middleware\VerifyLeonardoWebhookSignature;
use App\Models\AIImageSlot;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class LeonardoWebhookController extends Controller
{
    public function __construct()
    {
        if (config('services.leonardo.webhook_token')) {
            $this->middleware(VerifyLeonardoWebhookSignature::class);
        }
    }

    public function __invoke(Request $request)
    {
        $payload = $request->all();
        Log::info('Leonardo Webhook Payload:', $payload);

        if ($payload['type'] !== 'image_generation.complete') {
            return response()->json(['message' => 'Unsupported event type'], 200);
        }

        $generationData = $payload['data']['object'];
        $generationId = $generationData['id'];

        $image = Image::where('generation_service_name', 'leonardo')
            ->where('generation_id', $generationId)
            ->first();

        if (!$image) {
            Log::error("Image not found for Leonardo generation ID: $generationId");
            return response()->json(['message' => 'Image not found'], 404);
        }

        try {
            $this->updateImageWithGeneratedContent($image, $generationData);
            return response()->json(['message' => 'Image updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error("Error updating image: " . $e->getMessage());
            return response()->json(['message' => 'Error updating image'], 500);
        }
    }

    protected function updateImageWithGeneratedContent(Image $image, array $generationData)
    {
        $imageUrl = $generationData['images'][0]['url'];
        $content = Http::get($imageUrl)->body();

        $filename = 'generated_' . time() . '.jpg';
        $path = 'images/' . $filename;
        Storage::put($path, $content);

        $image->path = $path;
        $image->generation_settings = $generationData;
//        $image->token_cost = $generationData['apiDollarCost'] ?? null;
        $image->save();
    }
}
