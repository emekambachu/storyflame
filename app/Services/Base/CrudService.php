<?php

namespace App\Services\Base;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

/**
 * Class CrudService.
 */
class CrudService
{
    public function publishItem($item): array
    {
        if($item->status === 1){
            $item->status = 0;
            $item_name = $item->name ?? $item->title;
            $message = $item_name.' is pending';
        }else{
            $item->status = 1;
            $item_name = $item->name ?? $item->title;
            $message = $item_name.' is published';
        }
        $item->save();

        return [
            'item' => $item,
            'message' => $message,
        ];
    }

    public static function uploadAndCompressImage($request, $path, $width, $height, String $imageName): ?string
    {
        if($file = $request->file($imageName)) {
            $name = time() . $file->getClientOriginalName();
            // create path to directory
            if (!File::exists($path)){
                File::makeDirectory($path, 0777, true, true);
            }

            // start image conversion (Must install Image Intervention Package first)
            $convert_image = Image::make($file->path());

            // If image width and height is available, resize image
            // else upload just as is
            if($width !== null || $height !== null){
                $background = Image::canvas($width, $height);
                $convert_image->resize($width, $height, static function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $background->insert($convert_image, 'center');
                $background->save($path.'/'.$name);
            }else{
                // Upload just as is
                $convert_image->save($path.'/'.$name);
            }

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
