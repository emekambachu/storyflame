<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'name' => ['nullable', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user = User::create($credentials);

        Auth::login($user);

        return $this->successResponse('Registered', $user);
    }
}
