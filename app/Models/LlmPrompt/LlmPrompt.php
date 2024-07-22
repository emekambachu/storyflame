<?php

namespace App\Models\LlmPrompt;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LlmPrompt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'current_prompt_version_id',
        'updated_by',
    ];

    public function versions(): HasMany
    {
        return $this->hasMany(LlmPromptVersion::class, 'llm_prompt_id', 'id');
    }

    public function current_version(): BelongsTo
    {
        return $this->belongsTo(LlmPromptVersion::class, 'current_prompt_version_id', 'id');
    }

    public function updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
