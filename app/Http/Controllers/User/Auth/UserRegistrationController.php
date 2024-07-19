<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserSignOnRequest;
use App\Services\Auth\RegistrationService;
use App\Services\Base\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserRegistrationController extends Controller
{
    private RegistrationService $registration;
    public function __construct(RegistrationService $registration)
    {
        $this->registration = $registration;
    }

    public function signOn(UserSignOnRequest $request): JsonResponse
    {
        try {
            $response = $this->registration->userRegistration($request);
            return response()->json($response);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e, $request->email);
        }
    }

    public function verify(Request $request): JsonResponse
    {
        try {
            $response = $this->registration->userEmailVerification($request);
            return response()->json($response, isset($response['status_code']) && $response['status_code'] >= 400 ? $response['status_code'] : 200);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e, $request->email);
        }
    }
}
