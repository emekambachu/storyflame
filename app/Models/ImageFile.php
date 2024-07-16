<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'image_id',
        'path',
        'size',
    ];

    const ORIGINAL = 'original';
    const SIZE_THUMBNAIL = 'thumbnail';
    const SIZE_SMALL = 'small';
    const SIZE_MEDIUM = 'medium';
    const SIZE_LARGE = 'large';

    public static $maxFileSize = 3 * 1024;

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
