<?php

namespace App\Models\Summary;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummaryLink extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = [
        'summary_id',
        'linked_summary_id',
    ];


}
