<?php

namespace App\Services\Base;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * Class CrudService.
 */
class CrudService
{

    public static function uploadAndCompressImage($request, $path, $width, $height, String $imageName): ?string
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

            // get an extension of image
            if($extension === 'png'){
                $image->toPng(80);
            }else if($extension === 'jpeg' || $extension === 'jpg'){
                $image->toJpeg(80);
            }

            $image->save($path.'/'.$name);

            // Return full image upload path
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
