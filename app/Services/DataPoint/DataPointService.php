<?php

namespace App\Services\DataPoint;

use App\Models\DataPoint;

class DataPointService
{
    public function dataPoint(): DataPoint
    {
        return new DataPoint();
    }

    public function storeDataPoint($request): array
    {
        $inputs = $request->all();
        $dataPoint = $this->dataPoint()->create($inputs);
        return [
            'success' => true,
            'dataPoint' => $dataPoint,
        ];
    }

    public function updateDataPoint($request): array
    {
        $inputs = $request->all();
        $dataPoint = $this->dataPoint()->find($request->id);
        $dataPoint->update($inputs);

        return [
            'success' => true,
            'dataPoint' => $dataPoint,
        ];
    }

    public function deleteDataPoint($request): array
    {
        $dataPoint = $this->dataPoint()->find($request->id);
        $dataPoint->delete();

        return [
            'success' => true,
        ];
    }
}
