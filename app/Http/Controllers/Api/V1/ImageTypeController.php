<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImageTypeResource;
use App\Models\ImageType;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ImageTypeController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $imageTypes = ImageType::all();
        return ImageTypeResource::collection($imageTypes);
    }

    /**
     * @param $slug
     * @return ImageTypeResource
     */
    public function show($slug)
    {
        $imageType = ImageType::where('slug', $slug)->first();
        return new ImageTypeResource($imageType);
    }
}
