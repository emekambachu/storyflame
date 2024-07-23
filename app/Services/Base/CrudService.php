<?php

namespace App\Services\Base;

use Illuminate\Support\Facades\File;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * Class CrudService.
 */
class CrudService
{

    public static function uploadAndCompressImage(
        $request,
        string $path,
        string $imageName,
        $width = null,
        $height = null,
        $quality = 60
        ): ?string
    {
        if($file = $request->file($imageName)) {
            $extension = $file->getClientOriginalExtension();
            $name = time() . $file->getClientOriginalName();

            // create path to directory
            if (!File::exists($path)){
                File::makeDirectory($path, 0777, true, true);
            }

            // Instantiate intervention image manager
            $imageManager = new ImageManager(new Driver());
            $image = $imageManager->read($request->file($imageName));

            // If image width and height is available, resize image
            // else upload just as is
            if($width !== null || $height !== null){
                $image->scale($width, $height);
            }

            $image->encode(new AutoEncoder(quality: $quality));
            $image->save($path.'/'.$name);

            return $name;
        }
        return null;
    }

    public static function uploadAnyFile($request, String $path, String $fileName): ?string
    {
        if($file = $request->file($fileName)) {
            $name = time() . $file->getClientOriginalName();
            $file->move(public_path($path), $name);
            return $name;
        }
        return null;
    }

    public static function deleteFile($fileName, $filePath): void
    {
        if(File::exists(public_path() . '/'.$filePath.'/' . $fileName)){
            FILE::delete(public_path() . '/'.$filePath.'/' . $fileName);
        }
    }

    public static function deleteRelations($relation, $path = null): void
    {
        if($relation->count() > 0){
            foreach($relation as $item){
                $item_file = $item->photo ?? $item->document ?? $item->image ?? $item->file;
                if($path !== null && !empty($item_file) && File::exists(public_path() . '/'.$path.'/' . $item_file)) {
                    FILE::delete(public_path() . '/'.$path.'/' . $item_file);
                }
                $item->delete();
            }
        }
    }

}
