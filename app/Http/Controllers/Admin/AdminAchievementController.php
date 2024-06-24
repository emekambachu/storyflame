<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Achievement\AdminAchievementResource;
use App\Services\AchievementService;
use App\Services\Base\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->achievement->storeAchievement($request);
            return response()->json($data, $data['status_code'] ?? 200);
        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }
}
