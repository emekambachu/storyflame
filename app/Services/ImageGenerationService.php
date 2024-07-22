<?php

namespace App\Services;

use App\Models\ImageType;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class ImageGenerationService
{
    public function generatePrompt(Model $model, ImageType $imageType, array $data)
    {
        // Implement your logic to generate a prompt based on the model, image type, and provided data
        // This might involve calling your Python script or LLM
        // For now, we'll return a simple placeholder
        return "Generate an image of {$imageType->name} for {$model->name}";
    }

    public function generateImage($prompt)
    {
        // Implement your logic to call Leonardo AI or any other image generation service
        // For now, we'll return a placeholder URL
        return "https://placehold.co/600x400?text=Generated+Image";
    }


    /*
     * <?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('POST', 'https://cloud.leonardo.ai/api/rest/v1/generations', [
  'body' => '{"alchemy":false,"height":1024,"modelId":"b24e16ff-06e3-43eb-8d33-4416c2d75876","num_images":1,"presetStyle":"CINEMATIC","prompt":"a realistic movie poster, a scene set in a medieval world devastated by a catastrophic tsunami. The foreground features a stern Nordic General, his weathered face marked by sorrow and determination, clad in battle-worn armor. Next to him, a delicate Princess with wide, anxious eyes reflecting her PTSD, in a simple yet elegant gown. Behind them, a crumbling great hall contrasts with a sea of refugees struggling to rebuild amidst the ruins.Farther back, a grand but distant castle on a hilltop overlooks the turmoil below, hinting at the political intrigue and unsteady alliances. The sky is a mix of stormy clouds and fleeting sunlight, symbolizing both the lingering chaos and glimmers of hope. The overall atmosphere should evoke tension, resilience, and a sense of impending conflict, promising a narrative rich in negotiation, sacrifice, and the delicate balance of trust and betrayal. Shot on a 35mm lens with an Arri film camera. In the style of Game of Thrones, The Vikings, and The Last Kingdom","width":680,"guidance_scale":15,"negative_prompt":"No text.","promptMagic":true,"promptMagicStrength":0.33,"promptMagicVersion":"v2","public":false,"sd_version":"v3","num_inference_steps":12,"enhancePrompt":true,"enhancePromptInstruction":"for promotional movie poster or book cover with no text"}',
  'headers' => [
    'accept' => 'application/json',
    'authorization' => 'Bearer 2147f683-867f-445c-9c31-98349cebe510',
    'content-type' => 'application/json',
  ],
]);

echo $response->getBody();
     *
     * {
      "id": "6c95de60-a0bc-4f90-b637-ee8971caf3b0",
      "name": "Character Portraits",
      "description": "A model that's for creating awesome RPG characters of varying classes in a consistent style.",
      "nsfw": false,
      "featured": true,
      "generated_image": null
    }
    {
      "id": "b24e16ff-06e3-43eb-8d33-4416c2d75876",
      "name": "Leonardo Lightning XL",
      "description": "Our new high-speed generalist image gen model. Great at everything from photorealism to painterly styles.",
      "nsfw": false,
      "featured": false,
      "generated_image": {
        "id": "e1d0556b-7ccd-4568-8b1e-7d33e9db9e82",
        "url": "https://cdn.leonardo.ai/users/384ab5c8-55d8-47a1-be22-6a274913c324/generations/334022a8-7cea-43f9-a8a0-b9c2d232f32f/Default_an_ageing_astronaut_piloting_an_old_spaceship_0.jpg"
      }
    },
     */


    // create function to call leonardo image generation
    public function generateLeonardoImage($prompt, $imageTypePromptSettings)
    {
        $client = new Client();

        $body = array_merge(['prompt' => $prompt], config('image.leonardo_settings.prompt_settings'), $imageTypePromptSettings);

        $response = $client->request('POST', 'https://cloud.leonardo.ai/api/rest/v1/generations', [
            'body' => json_encode($body),
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . config('image.leonardo_settings.api_key'),
                'content-type' => 'application/json',
            ],
        ]);

        $response = json_decode($response->getBody());
        /* Response should look like:
        {
  "sdGenerationJob": {
    "generationId": "0111bbaa-f1b5-1b1b-a12b-ab123456a1ab",
    "apiCreditCost": 10
  }
}
        */

        return $response->generated_image->url;
    }

    public function createImage($model, $imageType, $generationSettings, $generationId, $generationServiceName = 'leonardo')
    {
        $image = $model->images()->create([
            'generation_settings' => $generationSettings,
            'generation_service_name' => $generationServiceName,
            'generation_id' => $generationId,
            'image_type_id' => $imageType->id,
            'token_cost' => $generationSettings['apiCreditCost'] ?? '0',
        ]);

        return $image;
    }
}
