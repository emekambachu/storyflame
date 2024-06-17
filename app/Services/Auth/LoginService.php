<?php

namespace App\Services\Auth;

use App\Services\Admin\AdminService;
use App\Services\Base\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class LoginService
{
    protected AdminService $admin;
    public function __construct(AdminService $admin){
        $this->admin = $admin;
    }

    public function loginWithToken(
        $request,
        String $webGuard,
        String $apiGuard,
        $queryBuilder
    ): array
    {
        if(Auth::guard($webGuard)->attempt([
            'email' => trim($request->email),
            'password' => trim($request->password),
        ])){
            // get Session
            $user = Auth::guard($webGuard)->user();

            // Create new token
            $token = $user->createToken($request->email, [$apiGuard])->plainTextToken;
            // Last login
            $queryBuilder->where('email', $request->email)->update([
                'last_login' => Carbon::now()->format('Y-m-d h:i:s'),
            ]);

            return [
                'success' => true,
                'user' => $user,
                'token' => $token,
            ];
        }

        return [
            'success' => false,
            'errors' => ['Incorrect credentials'],
        ];
    }

    public function adminLoginWithToken(
        $request,
        String $webGuard,
        String $apiGuard,
        $queryBuilder
    ): array
    {
        $credentials = $request->only('email', 'password');
        if(Auth::guard('admin')->attempt($credentials)){
            // get Session
            $user = Auth::guard($webGuard)->user();
            // Get Token
            $token = $user->createToken($request->email, [$apiGuard])->plainTextToken;
            // Last login
            $queryBuilder->where('email', $request->email)->update([
                'last_login' => Carbon::now()->format('Y-m-d h:i:s'),
            ]);

            $data = [
                'success' => true,
                'user' => $user,
                'token' => $token,
            ];
        }else{
            $data = [
                'success' => false,
                'error_message' => 'Incorrect credentials',
            ];
        }
        return $data;
    }

    public function sendPasswordReset($request, $query): array
    {
        $user = $query->where('email', trim($request->email))->first();
        $verificationToken = BaseService::randomCharacters(8, '1234567890');

        $user->verification_token = $verificationToken;
        $user->save();

        $emailArray = [
            'name' => $user->name,
            'email' => $user->email,
            'token' => $user->verification_token,
        ];

        // Send email to user
        try {
            BaseService::sendEmailGeneral(
                $emailArray,
                'emails.learning.auth.send-password-reset-token',
                'BRACE | Password Reset Token',
                $emailArray['email'],
                $emailArray['name']
            );
        }catch (\Exception $e) {
            return [
                'success' => true,
                'error_message' => 'Unable to send email',
                'message' => BaseService::tryCatchException($e),
            ];
        }

        return [
            'success' => true,
            'message' => 'password reset token sent',
        ];
    }

    public function updatePassword($request, $query): array
    {
        $input = $request->all();
        $user = $query->where('verification_token', $input['verification_token'])->first();

        $user->password = Hash::make($input['password']);
        $user->verification_token = Null;
        $user->save();

        return [
            'success' => true,
            'message' => 'Password updated',
        ];
    }

    public function authenticateUserWithToken($token): array
    {
        $user = PersonalAccessToken::findToken($token)->tokenable;
        return [
            'success' => true,
            'user' => $user,
        ];
    }

    public function LogoutAndDeleteAccessTokens($guard, $token): array
    {
        // Delete access tokens with email and logout of this guard
        $data = PersonalAccessToken::findToken($token)->tokenable;
        PersonalAccessToken::where('name', $data->email)->delete();

        Auth::guard($guard)->logout();
        return [
            'success' => true,
            'message' => 'Logged Out',
        ];
    }

}
