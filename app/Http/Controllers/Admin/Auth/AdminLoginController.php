<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
use App\Services\Auth\LoginService;
use App\Services\Base\BaseService;
use App\Services\User\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    private LoginService $login;
    private UserProfileService $user;
    public function __construct(LoginService $login, UserProfileService $user){
        $this->login = $login;
        $this->user = $user;
    }

    public function login(AdminLoginRequest $request): JsonResponse
    {
        try {
            $data = $this->login->loginWithToken(
                $request,
                $this->user->user()
            );
            return response()->json($data, Response::HTTP_OK);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

//    public function login(AdminLoginRequest $request): \Illuminate\Http\JsonResponse
//    {
//        try {
//            $data = $this->login->loginWithToken(
//                $request,
//                'admin',
//                'admin-api',
//                $this->admin->admin()
//            );
//            return response()->json($data, Response::HTTP_OK);
//
//        } catch (\Exception $e) {
//            return BaseService::tryCatchException($e);
//        }
//    }

    public function authenticate(Request $request): JsonResponse
    {
        try {
            $data = $this->login->authenticateUserWithToken($request->token);
            return response()->json($data, Response::HTTP_OK);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            Auth::user()?->tokens()?->delete();
            return response()->json([
                'success' => true,
            ]);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

}
