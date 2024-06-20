<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class AdminAchievementController extends Controller
{
    protected AchievementService $achievement;
    public function __construct(AchievementService $achievement)
    {
        $this->achievement = $achievement;
    }

    public function index(){
        return response()->json([
            'success' => true,
            'achievements' => $this->achievement->getAchievements()
        ]);
    }
}
