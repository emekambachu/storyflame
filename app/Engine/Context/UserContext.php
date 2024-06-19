<?php

namespace App\Engine\Context;

use App\Engine\Config\UserEngineConfig;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @template-extends BaseContext<User>
 */
class UserContext extends BaseContext implements ContextInterface
{
    function getContextClass(): ?string
    {
        return null;
    }


    public function __construct($model = null)
    {
        parent::__construct($model);
        $this->config = new UserEngineConfig();
    }

    public function getModel()
    {
        return $this->model;
    }

    public function stories(): HasMany
    {
        return $this->getModel()->stories();
    }

    public function characters(): HasMany
    {
        return $this->getModel()->characters();
    }

    public function plots(): HasMany
    {

    }

    public function sequences(): HasMany
    {
        // TODO: Implement sequences() method.
    }

    protected function onDataPointSaved(string $key, mixed $value): void
    {
        $model = $this->getModel();
        switch ($key) {
            case 'name':
                $model->name = $value;
                break;
            default:
                break;
        }
        if ($model->isDirty()) {
            $model->save();
        }
    }

    protected function getCurrentData(): array
    {
        return [
            'Writer' => [
                [
                    'name' => $this->getModel()->name,
                    'type' => 'Writer',
                    'existing_data_points' => $this->getModel()
                        ->dataPointsToArray()
                ]
            ]
        ];
    }

    protected function getContextName(): string
    {
        return $this->getModel()?->name ?? 'New Writer';
    }

    protected function getContextGoal(): string
    {
        return 'Learn about the writer, and generally about their stories.';
    }
}
