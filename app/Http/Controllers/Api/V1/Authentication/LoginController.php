<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return $this->successResponse('Authenticated', Auth::user());
        }

        return $this->errorResponse('Invalid credentials', 401);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->successResponse('Logged out');
    }

    public function user(Request $request): JsonResponse
    {
        abort(401);
        return $this->successResponse('User', Auth::user());
    }
}
