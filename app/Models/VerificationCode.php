<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationCode extends Model
{
	use HasUuids;

	protected $fillable = [
		'user_id',
		'otp',
		'expire_at',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	protected function casts()
	{
		return [
			'expire_at' => 'datetime',
		];
	}
}
