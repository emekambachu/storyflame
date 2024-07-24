<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\AuthCodeMail;
use App\Models\User;
use App\Models\VerificationCode;
use App\Services\Referral\ReferralService;
use App\Services\User\UserProfileService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    protected ReferralService $referral;
    protected UserProfileService $user;
    public function __construct(ReferralService $referral, UserProfileService $user)
    {
        $this->referral = $referral;
        $this->user = $user;
    }

    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['exclude_unless:otp,null', 'string'],
            'otp' => ['sometimes', 'digits:6']
        ]);

        if (isset($credentials['password']) && $credentials['password'] !== '') {
            if (Auth::attempt($credentials)) {
                // Generate API token
                /* @var User $user */
                $user = Auth::user();
                $token = $user->createToken('API Token')->plainTextToken;

                return $this->successResponse('Authenticated', [
                    'user' => $user,
                    'token' => $token
                ]);
            } else {
                return $this->errorResponse('Invalid credentials', 401);
            }
        } else {
            /* @var User|null $user */
            $user = User::firstWhere('email', $credentials['email']);
            if ($user) {
                $verificationCode = $user->verificationCodes()->latest()->first();
                $now = Carbon::now();
                if ($user->email_verified_at === null) {
                    $user->verifyEmail();
                }

                if ($user->referral_code === null) {
                    $user->createUniqueReferralCode();
                    if(!empty($credentials['referred_by_code'])){ {
                        $referredByUser = $this->user->user()->where('referral_code', $credentials['referred_by_code'])->first();
                        if(!$referredByUser){
                            return $this->errorResponse('Invalid referral code', 401);
                        }
                        $user->referred_by = $referredByUser->id;
                        $user->save();
                        $this->referral->addReferrerToReceiver($referredByUser->id, $user->id);
                    }
                }

                if ($verificationCode && $now->isBefore($verificationCode->expire_at) && $verificationCode->otp === $credentials['otp']) {
                    Auth::login($user); // For session-based authentication (not recommended for APIs)
                    $user->startTrial();

                    // Generate API token
                    $token = $user->createToken('API Token')->plainTextToken;

                    return $this->successResponse('Authenticated', [
                        'user' => $user,
                        'token' => $token
                    ]);
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
        Log::info('PUBLIC FUNCTION USER CALLED');
        Log::info('Request data: ', $request->all());
        $user = Auth::user();
        if ($user) {
            return $this->successResponse('User', UserResource::make($user));
        } else {
            return $this->errorResponse('Unauthenticated', 401);
        }
        return $this->successResponse('User', UserResource::make(Auth::user()));
    }

    private function getVerificationCode(User $user): VerificationCode
    {
        Log::info('GET VERIFICATION CODE FUNCTION CALLED');
        Log::info('Request data: ', $user->toArray());
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
            'referral_code' => ['sometimes', 'nullable', 'string'],
        ]);

        $referringUser = null;
        if (isset($credentials['referral_code']) && $credentials['referral_code'] !== null) {
            Log::info('Referral code is b: ' . $credentials['referral_code']);

            $referringUser = User::findByReferralCode($credentials['referral_code']);
            Log::info('Referring user is: ' . $referringUser->email);
        }

        $user = User::firstOrCreate(
            ['email' => $credentials['email']],
            ['referred_by' => $referringUser->id ?? null]
        );

        if($referringUser && $user->referred_by === null) {
            $user->referred_by = $referringUser->id;
            $user->save();
        }

        $otp_sent = false;
        if ($user->password === null || (isset($credentials['use_code']) && $credentials['use_code'])) {
            $verificationCode = $this->getVerificationCode($user);
            Mail::to($user)->send(new AuthCodeMail($verificationCode->otp));
            $otp_sent = true;
        }

        return $this->successResponse('Success', [
            'pwd' => $user->password !== null,
            'email' => $user->email,
            'emailed_verified_at' => $user->email_verified_at !== null,
            'referred_by' => $user->referred_by,
            'otp_sent' => $otp_sent,
        ]);
    }
}
