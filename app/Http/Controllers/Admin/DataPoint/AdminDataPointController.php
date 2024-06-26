<?php

namespace App\Http\Controllers\Admin\DataPoint;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataPoint\AdminStoreDataPointRequest;
use App\Http\Requests\Admin\DataPoint\AdminUpdateDataPointRequest;
use App\Http\Resources\Admin\DataPoint\AdminDataPointResource;
use App\Services\Base\BaseService;
use App\Services\DataPoint\DataPointService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDataPointController extends Controller
{
    protected DataPointService $dataPoint;
    public function __construct(DataPointService $dataPoint)
    {
        $this->dataPoint = $dataPoint;
    }

    public function index(): JsonResponse
    {
        try {
            $data = $this->dataPoint->dataPoint()
                ->with('categories', 'achievements', 'summaries')
                ->latest()->get();
            return response()->json([
                'success' => true,
                'achievements' => AdminDataPointResource::collection($data),
                'total' => $data->count(),
            ]);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function indexMin(): JsonResponse
    {
        try {
            $data = $this->dataPoint->dataPoint()->select('id', 'name')->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data_points' => $data,
            ]);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function store(AdminStoreDataPointRequest $request): JsonResponse
    {
        try {
            $data = $this->dataPoint->storeDataPoint($request);
            return response()->json($data, $data['status_code'] ?? 200);
        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function update(AdminUpdateDataPointRequest $request, $item_id): JsonResponse
    {
        try {
            $data = $this->dataPoint->updateDataPoint($request, $item_id);
            return response()->json($data, $data['status_code'] ?? 200);
        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function delete($item_id): JsonResponse
    {
        try {
            $data = $this->dataPoint->deleteDataPoint($item_id);
            return response()->json($data, $data['status_code'] ?? 200);
        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }



}
