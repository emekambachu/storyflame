<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyLeonardoWebhookSignature
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorizationHeader = $request->header('Authorization');

        if ($this->isInvalidSignature($authorizationHeader)) {
            throw new AccessDeniedHttpException('Invalid Leonardo webhook signature.');
        }

        return $next($request);
    }

    /**
     * Validate the Leonardo webhook signature.
     *
     * @param string|null $authorizationHeader
     * @return bool
     */
    protected function isInvalidSignature(?string $authorizationHeader): bool
    {
        if (empty($authorizationHeader)) {
            return true;
        }

        // The Authorization header should be in the format "Bearer <token>"
        $parts = explode(' ', $authorizationHeader);

        if (count($parts) !== 2 || $parts[0] !== 'Bearer') {
            return true;
        }

        $token = $parts[1];

        // Compare the token with the one stored in your configuration
        $expectedToken = config('services.leonardo.webhook_token');

        return !hash_equals($expectedToken, $token);
    }

//Sample Request to your Webhook
//Below is a sample request made by Leonardo to a webhook callback.
//
//
//curl -X 'POST' \
//'https://webhook.site/cc21af5f-4caa-498e-8f26-20c664680b73' \
//-H 'connection: close' \
//-H 'host: webhook.site' \
//-H 'accept-encoding: gzip, compress, deflate, br' \
//-H 'content-length: 3166' \
//-H 'user-agent: axios/1.4.0' \
//-H 'authorization: Bearer abcd' \
//-H 'content-type: application/json' \
//-H 'accept: application/json, text/plain, */*' \
//-d '{
//    "type": "image_generation.complete",
//    "object": "generation",
//    "timestamp": 1699490546932,
//    "api_version": "v1",
//    "data": {
//        "object": {
//            "id": "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX",
//            "createdAt": "2023-11-09T00:42:22.707Z",
//            "updatedAt": "2023-11-09T00:42:26.740Z",
//            "userId": "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX",
//            "public": false,
//            "flagged": false,
//            "nsfw": false,
//            "status": "COMPLETE",
//            "coreModel": "SD",
//            "guidanceScale": 7,
//            "imageHeight": 512,
//            "imageWidth": 512,
//            "inferenceSteps": 30,
//            "initGeneratedImageId": null,
//            "initImageId": null,
//            "initStrength": null,
//            "initType": null,
//            "initUpscaledImageId": null,
//            "modelId": "6bef9f1b-29cb-40c7-b9df-32b51c1f67d3",
//            "negativePrompt": "",
//            "prompt": "An oil painting of a cat",
//            "quantity": 1,
//            "sdVersion": "v2",
//            "tiling": false,
//            "imageAspectRatio": null,
//            "tokenCost": 0,
//            "negativeStylePrompt": "",
//            "seed": "905778432",
//            "scheduler": "EULER_DISCRETE",
//            "presetStyle": null,
//            "promptMagic": false,
//            "canvasInitImageId": null,
//            "canvasMaskImageId": null,
//            "canvasRequest": false,
//            "api": true,
//            "poseImage2Image": false,
//            "imagePromptStrength": null,
//            "category": null,
//            "poseImage2ImageType": null,
//            "highContrast": false,
//            "apiDollarCost": "9",
//            "poseImage2ImageWeight": null,
//            "alchemy": null,
//            "contrastRatio": null,
//            "highResolution": null,
//            "expandedDomain": null,
//            "promptMagicVersion": null,
//            "unzoom": null,
//            "unzoomAmount": null,
//            "apiKeyId": "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX",
//            "photoReal": false,
//            "promptMagicStrength": null,
//            "photoRealStrength": null,
//            "imageToImage": false,
//            "controlnetsUsed": false,
//            "model": {
//                "id": "6bef9f1b-29cb-40c7-b9df-32b51c1f67d3",
//                "createdAt": "2023-01-06T01:02:38.315Z",
//                "updatedAt": "2023-03-01T11:45:06.428Z",
//                "name": "Leonardo Creative",
//                "description": "An alternative finetune of SD 2.1 that brings a little more creative interpretation to the mix.",
//                "public": true,
//                "userId": "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX",
//                "flagged": false,
//                "nsfw": false,
//                "official": true,
//                "status": "COMPLETE",
//                "classPrompt": null,
//                "coreModel": "SD",
//                "initDatasetId": null,
//                "instancePrompt": null,
//                "sdVersion": "v2",
//                "trainingEpoch": null,
//                "trainingSteps": null,
//                "tokenCost": null,
//                "batchSize": 4,
//                "learningRate": null,
//                "type": "GENERAL",
//                "modelHeight": 768,
//                "modelWidth": 768,
//                "leonardoInstancePrompt": null,
//                "trainingStrength": "MEDIUM",
//                "featured": false,
//                "featuredImageId": null,
//                "featuredPosition": 4,
//                "api": false,
//                "favouriteCount": 0,
//                "imageCount": 2416039,
//                "enhancedModeration": false,
//                "apiDollarCost": null,
//                "apiKeyId": null,
//                "modelLRN": null
//            },
//            "images": [
//                {
//                    "id": "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX",
//                    "createdAt": "2023-11-09T00:42:26.733Z",
//                    "updatedAt": "2023-11-09T00:42:26.733Z",
//                    "userId": "ef8b8386-94f7-48d1-b10e-e87fd4dee6e6",
//                    "url": "https://cdn.leonardo.ai/users/XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX/generations/XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX/Leonardo_Creative_An_oil_painting_of_a_cat_0.jpg",
//                    "generationId": "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX",
//                    "nobgId": null,
//                    "nsfw": false,
//                    "likeCount": 0,
//                    "trendingScore": 0,
//                    "public": false
//                }
//            ],
//            "apiKey": {
//                "id": "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX",
//                "createdAt": "2023-11-07T00:11:07.274Z",
//                "userId": "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX",
//                "key": "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX",
//                "lastUsed": "2023-11-07T00:11:07.274Z",
//                "name": "webhook with key",
//                "type": "PRODUCTION",
//                "webhookCallbackUrl": "https://webhook.site/cc21af5f-4caa-498e-8f26-20c664680b73",
//                "webhookCallbackApiKey": "abcd"
//            }
//        }
//    }
//}


    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);



    }
}


//namespace Laravel\Paddle\Http\Middleware;
//
//use Closure;
//use Illuminate\Http\Request;
//use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
//
///**
// * @see https://developer.paddle.com/webhook-reference/verifying-webhooks
// */
//class VerifyWebhookSignature
//{
//    public const SIGNATURE_HEADER = 'Paddle-Signature';
//    public const HASH_ALGORITHM_1 = 'h1';
//
//    protected ?int $maximumVariance = 5;
//
//    /**
//     * Handle the incoming request.
//     *
//     * @param \Illuminate\Http\Request $request
//     * @param \Closure $next
//     * @return \Illuminate\Http\Response
//     *
//     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
//     */
//    public function handle(Request $request, Closure $next)
//    {
//        $signature = $request->header(self::SIGNATURE_HEADER);
//
//        if ($this->isInvalidSignature($request, $signature)) {
//            throw new AccessDeniedHttpException('Invalid webhook signature.');
//        }
//
//        return $next($request);
//    }
//
//    /**
//     * Validate signature.
//     *
//     * @param \Illuminate\Http\Request $request
//     * @param string $signature
//     * @return bool
//     */
//
//    //the signature is not $signature[0] it's $signature
//    //the true it's false and false it's true when if ($this->isInvalidSignature($request, $signature)) { throw new AccessDeniedHttpException('Invalid webhook signature.'); }
//    protected function isInvalidSignature(Request $request, $signature)
//    {
//        if (empty($signature)) {
//            return true;
//        }
//
//        [
//            $timestamp,
//            $hashes
//        ] = $this->parseSignature($signature);
//
//        if ($this->maximumVariance > 0 && time() > $timestamp + $this->maximumVariance) {
//            return true;
//        }
//
//        $secret = config('cashier.webhook_secret');
//        $data = $request->getContent();
//
//        foreach ($hashes as $hashAlgorithm => $possibleHashes) {
//            $hash = match ($hashAlgorithm) {
//                'h1' => hash_hmac('sha256', "{$timestamp}:{$data}", $secret),
//            };
//
//            foreach ($possibleHashes as $possibleHash) {
//                if (hash_equals($hash, $possibleHash)) {
//                    return false;
//                }
//            }
//        }
//
//        return true;
//    }
//
//    /**
//     * Parse the signature header.
//     *
//     * @param string $header
//     * @return array
//     */
//    public function parseSignature(string $header): array
//    {
//        $components = [
//            'ts' => 0,
//            'hashes' => [],
//        ];
//
//        foreach (explode(';', $header) as $part) {
//            if (str_contains($part, '=')) {
//                [
//                    $key,
//                    $value
//                ] = explode('=', $part, 2);
//
//                match ($key) {
//                    'ts' => $components['ts'] = (int)$value,
//                    'h1' => $components['hashes']['h1'][] = $value,
//                };
//            }
//        }
//
//        return [
//            $components['ts'],
//            $components['hashes'],
//        ];
//    }
//}
