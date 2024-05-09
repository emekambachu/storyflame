<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function downloadImage(string $url): string
    {
        $filename = 'images/' . now()->format('Y-m-d') . '-' . uniqid() . '.' . pathinfo($url, PATHINFO_EXTENSION);

        $imageFile = file_get_contents($url);

        Storage::disk('public')->put($filename, $imageFile);

        return $filename;
    }
}
