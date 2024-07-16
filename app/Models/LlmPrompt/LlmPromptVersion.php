<?php

namespace App\Models\LlmPrompt;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LlmPromptVersion extends Model
{
    use HasFactory;
    protected $fillable = [
        'llm_prompt_id',
        'name',
        'slug',
        'prompt_value',
        'updated_by_user_id',
    ];

    public function llm_prompt(): BelongsTo
    {
        return $this->belongsTo(LlmPrompt::class, 'llm_prompt_id', 'id');
    }

    public function updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id', 'id');
    }
}
