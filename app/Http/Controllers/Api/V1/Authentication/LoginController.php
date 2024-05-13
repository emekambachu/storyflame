<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\AuthCodeMail;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['exclude_unless:otp,null', 'string'],
            'otp' => ['sometimes', 'digits:6']
        ]);

        if (isset($credentials['password'])) {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return $this->successResponse('Authenticated', Auth::user());
            }
        } else {
            $user = User::firstWhere('email', $credentials['email']);
            if ($user) {
                $verificationCode = $user->verificationCodes()->latest()->first();
                $now = Carbon::now();
                if ($verificationCode && $now->isBefore($verificationCode->expire_at) && $verificationCode->otp === $credentials['otp']) {
                    Auth::login($user);
                    $request->session()->regenerate();
                    return $this->successResponse('Authenticated', Auth::user());
                }
            }
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
        return $this->successResponse('User', UserResource::make(Auth::user()));
    }

    private function getVerificationCode(User $user): VerificationCode
    {
        $verificationCode = $user->verificationCodes()->latest()->first();
        $now = Carbon::now();
        if ($verificationCode && $now->isBefore($verificationCode->expire_at)) {
            return $verificationCode;
        }

        $code = rand(1, 999999);
        $code = str_pad($code, 6, '0', STR_PAD_LEFT);

        return VerificationCode::create([
            'user_id' => $user->id,
            'otp' => $code,
            'expire_at' => $now->addMinutes(config('auth.otp_minutes')),
        ]);
    }

    public function federate(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'use_code' => ['sometimes', 'boolean'],
        ]);

        $user = User::firstWhere('email', $credentials['email']);

        if ($user) {
            $otp_sent = false;
            if ($user->password === null || $credentials['use_code']) {
                $verificationCode = $this->getVerificationCode($user);
                Mail::to($user)->send(new AuthCodeMail($verificationCode->otp));
                $otp_sent = true;
            }

            return $this->successResponse('Success', [
                'pwd' => $user->password !== null,
                'otp_sent' => $otp_sent,
            ]);
        }
        return $this->errorResponse('User not found', 404);
    }
}
