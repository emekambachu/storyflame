<?php

namespace App\Models\Summary;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Summary extends Model
{
    use SoftDeletes, HasUuids, HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'item_id',
        'location',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
