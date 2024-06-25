<?php

namespace App\Http\Controllers\Admin\DataPoint;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\DataPoint\AdminDataPointResource;
use App\Services\Base\BaseService;
use App\Services\DataPoint\DataPointService;
use Illuminate\Http\JsonResponse;

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



}
