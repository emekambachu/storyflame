<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Base\BaseService;
use App\Services\User\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

}
