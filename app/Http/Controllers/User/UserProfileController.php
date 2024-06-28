<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Base\BaseService;
use App\Services\User\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    protected UserProfileService $user;
    public function __construct(UserProfileService $user)
    {
        $this->user = $user;
    }

    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $data = $this->user->updateUserProfile($request);
            return response()->json($data);

        } catch(\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function profile(): JsonResponse
    {
        try {
            $data = $this->user->user()->find(Auth::user());
            return response()->json([
                'status' => 'success',
                'user' => $data
            ]);
        }catch(\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

}
