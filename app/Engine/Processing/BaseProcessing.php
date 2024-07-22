<?php

namespace App\Engine\Processing;

use App\Engine\Context\BaseContext;
use App\Models\Concerns\ModelWithId;
use App\Models\TokenUsage;
use App\Models\User;

abstract class BaseProcessing implements ProcessingInterface
{
    public function __construct(
        protected BaseContext|null $context,
        private ModelWithId|null   $target = null,
    )
    {
    }

    protected function saveUsage(string $key, array $usage): void
    {
        if (array_is_list($usage)) {
            foreach ($usage as $u) {
                $this->saveUsage($key, $u);
            }
            return;
        }

        TokenUsage::create([
            'key' => $key,
            'user_id' => auth()->id(),
            'target_id' => $this->target?->id ?? auth()->id(),
            'target_type' => $this->target?->getMorphClass() ?? User::class,
            'model' => $usage['model'],
            'input_tokens' => $usage['input_tokens'],
            'output_tokens' => $usage['output_tokens'],
        ]);
    }

    public function getTarget(): ModelWithId
    {
        return $this->target;
    }

    public function setTarget(ModelWithId $target): void
    {
        $this->target = $target;
    }

    public function setContext(BaseContext $context): void
    {
        $this->context = $context;
    }
}
