<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alias extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
    ];
}
