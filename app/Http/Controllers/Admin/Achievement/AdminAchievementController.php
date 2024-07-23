<?php

namespace App\Http\Controllers\Admin\Achievement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Achievement\AdminStoreAchievementRequest;
use App\Http\Requests\Admin\Achievement\AdminUpdateAchievementRequest;
use App\Http\Resources\Admin\Achievement\AdminAchievementResource;
use App\Services\AchievementService;
use App\Services\Base\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminAchievementController extends Controller
{
    protected AchievementService $achievement;
    public function __construct(AchievementService $achievement)
    {
        $this->achievement = $achievement;
    }

    public function index(): JsonResponse
    {
        try {
            $data = $this->achievement->achievement()
                ->with('categories')
                ->latest()->get();
            return response()->json([
                'success' => true,
                'achievements' => AdminAchievementResource::collection($data),
                'total' => $data->count(),
            ]);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function indexMin(): JsonResponse
    {
        try {
            $data = $this->achievement->achievement()->select('id', 'name')->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'achievements' => $data,
            ]);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function store(AdminStoreAchievementRequest $request): JsonResponse
    {
        try {
            $data = $this->achievement->storeAchievement($request);
            return response()->json($data, $data['status_code'] ?? 200);
        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function update(AdminUpdateAchievementRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->achievement->updateAchievement($request, $id);
            return response()->json($data, $data['status_code'] ?? 200);
        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $data = $this->achievement->deleteAchievement($id);
            return response()->json($data, $data['status_code'] ?? 200);
        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }
}
