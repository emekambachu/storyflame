<?php

namespace App\Models\LlmPrompt;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LlmPrompt extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'current_prompt_version_id',
        'updated_by_id',
    ];

    public function versions(): HasMany
    {
        return $this->hasMany(LlmPromptVersion::class, 'llm_prompt_id', 'id');
    }

    public function currentVersion(): BelongsTo
    {
        return $this->belongsTo(LlmPromptVersion::class, 'id', 'current_prompt_version_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'updated_by_id');
    }
}
