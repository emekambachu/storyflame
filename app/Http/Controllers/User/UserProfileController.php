<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Profile\UserProfileUpdateAvatarRequest;
use App\Http\Requests\User\Profile\UserProfileUpdateBioRequest;
use App\Http\Requests\User\Profile\UserProfileUpdatePasswordRequest;
use App\Http\Resources\UserResource;
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

    public function updateBio(UserProfileUpdateBioRequest $request): JsonResponse
    {
        try {
            $data = $this->user->updateUserBio($request);
            return response()->json($data);

        } catch(\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function updatePassword(UserProfileUpdatePasswordRequest $request): JsonResponse
    {
        try {
            $data = $this->user->updateUserPassword($request);
            return response()->json($data);

        } catch(\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function updateAvatar(UserProfileUpdateAvatarRequest $request): JsonResponse
    {
        try {
            $data = $this->user->updateUserAvatar($request);
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
                'user' => $data,
                'user_resource' => UserResource::make($data)
            ]);
        }catch(\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

}
