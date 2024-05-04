<?php

namespace App\Models;

use App\Models\Concerns\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
	use SoftDeletes, HasUuids, HasFactory, HasSchemalessAttributes;

	protected $fillable = [
		'type',
		'user_id',
		'chat_id',
		'content',
		'extra_attributes',
	];

	public function scopeNotSystem($query)
	{
		return $query->where('type', '!=', 'system');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function voiceMessage(): HasOne
	{
		return $this->hasOne(ChatVoiceMessage::class);
	}
}
