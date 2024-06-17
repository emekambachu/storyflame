<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
use App\Services\Admin\AdminService;
use App\Services\Auth\LoginService;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    private LoginService $login;
    private AdminService $admin;
    public function __construct(LoginService $login, AdminService $admin){
        $this->login = $login;
        $this->admin = $admin;
    }

    public function adminLogin(AdminLoginRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->login->adminLoginWithToken(
                $request,
                'admin',
                'admin-api',
                $this->admin->admin()
            );
            return response()->json($data, Response::HTTP_OK);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function login(AdminLoginRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->login->loginWithToken(
                $request,
                'admin',
                'admin-api',
                $this->admin->admin()
            );
            return response()->json($data);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    use AuthenticatesUsers {
        logout as performLogout;
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        try {
            Auth::guard('admin')->logout();
            if(Auth::user()){
                Auth::user()->tokens()->delete();
            }
            return response()->json([
                'success' => true,
                'message' => 'Logged Out',
            ]);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

}
