<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImageTypeResource;
use App\Models\ImageType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

//TODO: Hey @victor, would you add these to the Admin frontend?  And we'll need to be able add/remove Summaries and DataPoints to each using ImageTypeSchema
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
     * @param $id
     * @return ImageTypeResource
     */
    public function show($id)
    {
        $imageType = ImageType::findOrFail($id);
        return new ImageTypeResource($imageType);
    }

    /**
     * @param Request $request
     * @return ImageTypeResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'purpose' => 'nullable|string',
            'creation_prompt' => 'nullable|string',
            'example_prompt' => 'nullable|string',
        ]);

        $imageType = ImageType::create($request->all());
        return new ImageTypeResource($imageType);
    }

    /**
     * @param Request $request
     * @param $id
     * @return ImageTypeResource
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'purpose' => 'nullable|string',
            'creation_prompt' => 'nullable|string',
            'example_prompt' => 'nullable|string',
        ]);

        $imageType = ImageType::findOrFail($id);
        $imageType->update($request->all());
        return new ImageTypeResource($imageType);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $imageType = ImageType::findOrFail($id);
        $imageType->delete();

        return response()->json(null, 204);
    }
}
