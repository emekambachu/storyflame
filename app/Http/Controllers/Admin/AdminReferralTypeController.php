<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Base\BaseService;
use App\Services\Referral\ReferralService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminReferralTypeController extends Controller
{
    protected ReferralService $referralType;
    public function __construct(ReferralService $referralType)
    {
        $this->referralType = $referralType;
    }

    public function index(): JsonResponse
    {
        try{
            $response = $this->referralType->referralTypes()->get();
            return response()->json([
                'success' => true,
                'referral_types' => $response
            ]);

        }catch(\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try{
            $response = $this->referralType->referralTypes()->store($request->all());
            return response()->json([
                'success' => true,
                'referral_type' => $response
            ]);

        }catch(\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }

    public function update(Request $request, string $slug): JsonResponse
    {
        try{
            $response = $this->referralType->referralTypes()->update($request->all(), $slug);
            return response()->json([
                'success' => true,
                'referral_type' => $response
            ]);

        }catch(\Exception $e){
            return BaseService::tryCatchException($e);
        }
    }
}
