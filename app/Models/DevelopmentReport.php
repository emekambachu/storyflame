<?php

namespace App\Models;

use App\Models\Summary\Summary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevelopmentReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'report_credits',
        'processing_time',
        'publish_at',
    ];

    public function summaries()
    {
        return $this->belongsToMany(Summary::class, 'development_report_summaries')
            ->withPivot('order')
            ->orderBy('development_report_summaries.order')
            ->withTimestamps();
    }

    public function userDevelopmentReports()
    {
        return $this->hasMany(UserDevelopmentReport::class);
    }
}
