<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Services\Base\BaseService;
use App\Services\Referral\ReferralService;
use Illuminate\Http\JsonResponse;

class ReferralTypeController extends Controller
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
}
