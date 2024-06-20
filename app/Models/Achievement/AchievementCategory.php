<?php

namespace App\Models\Achievement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievementCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'achievement_id',
        'category_id',
    ];
}
