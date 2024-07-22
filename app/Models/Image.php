<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;


class Image extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'path',
        'group',
        'imageable_id',
        'imageable_type',
        'image_type_id',
        'generation_service_name',
        'generation_id',
        'generation_settings',
        'token_cost'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            DB::transaction(function () use ($image) {
                $image->save();
                if($image->path !== null){
                    $image->createImageFiles();
                }
            });
        });

        static::updating(function ($image) {
            if ($image->isDirty('path') && $image->path !== null) {
                DB::transaction(function () use ($image) {
                    $image->files()->delete();
                    $image->createImageFiles();
                });
            }
        });
    }

    /**
     * @return HasMany
     */
    public function imageType(): HasMany
    {
        return $this->hasMany(ImageType::class);
    }

    /**
     * @return MorphTo
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(ImageFile::class);
    }

    public function createImageFiles()
    {
        $sizes = config('image.sizes');
        $originalPath = $this->path;

        foreach ($sizes as $size => $settings) {
            $filePath = $this->resizeImage($originalPath, $settings);
            $this->files()->create([
                'size' => $size,
                'path' => $filePath,
            ]);
        }
    }

    protected function resizeImage($path, $settings)
    {
        if ($settings['width'] === 0 && $settings['height'] === 0) {
            return $path; // Return the original path for the 'original' size
        }

        $image = InterventionImage::make(storage_path('app/' . $path));
        $image->fit($settings['width'], $settings['height']);
        $newPath = 'images/' . pathinfo($path, PATHINFO_FILENAME) . '_' . $settings['width'] . 'x' . $settings['height'] . '.' . pathinfo($path, PATHINFO_EXTENSION);
        $image->save(storage_path('app/' . $newPath), $settings['quality']);

        return $newPath;
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }
}
