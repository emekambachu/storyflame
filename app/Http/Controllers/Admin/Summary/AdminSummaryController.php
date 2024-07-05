<?php

namespace App\Http\Controllers\Admin\Summary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataPoint\AdminStoreDataPointRequest;
use App\Http\Requests\Admin\DataPoint\AdminUpdateDataPointRequest;
use App\Http\Requests\Admin\Summary\AdminStoreSummaryRequest;
use App\Http\Requests\Admin\Summary\AdminUpdateSummaryRequest;
use App\Http\Resources\Admin\DataPoint\AdminDataPointResource;
use App\Http\Resources\Admin\Summary\AdminSummaryResource;
use App\Services\Base\BaseService;
use App\Services\DataPoint\DataPointService;
use App\Services\Summary\SummaryService;
use Illuminate\Http\JsonResponse;

class AdminSummaryController extends Controller
{
    protected SummaryService $summary;
    public function __construct(SummaryService $summary)
    {
        $this->summary = $summary;
    }

    public function index(): JsonResponse
    {
        try {
            $data = $this->summary->summary()
                ->with('categories', 'dataPoints:id', 'summaries:id')
                ->latest()->get();
            return response()->json([
                'success' => true,
                'summaries' => AdminSummaryResource::collection($data),
                'total' => $data->count(),
            ]);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function indexMin(): JsonResponse
    {
        try {
            $data = $this->summary->summary()->select('id', 'name')->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'summaries' => $data,
            ]);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function store(AdminStoreSummaryRequest $request): JsonResponse
    {
        try {
            $data = $this->summary->storeSummary($request);
            return response()->json($data, $data['status_code'] ?? 200);
        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function update(AdminUpdateSummaryRequest $request, $item_id): JsonResponse
    {
        try {
            $data = $this->summary->updateSummary($request, $item_id);
            return response()->json($data, $data['status_code'] ?? 200);
        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function delete($item_id): JsonResponse
    {
        try {
            $data = $this->summary->deleteSummary($item_id);
            return response()->json($data, $data['status_code'] ?? 200);
        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }
}
