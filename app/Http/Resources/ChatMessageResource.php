<?php

namespace App\Http\Resources;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ChatMessage */
class ChatMessageResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'type' => $this->type,
			'content' => $this->content,
			'options' => $this->when($this->extra_attributes->has('options'), $this->extra_attributes->get('options')),
			'title' => $this->when($this->extra_attributes->has('title'), $this->extra_attributes->get('title')),
			'subtitle' => $this->when($this->extra_attributes->has('subtitle'), $this->extra_attributes->get('subtitle')),
		];
	}
}
