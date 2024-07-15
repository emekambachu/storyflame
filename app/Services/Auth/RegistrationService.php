<?php

namespace App\Services\Auth;

use App\Events\UserRegistrationEvent;
use App\Http\Resources\UserResource;
use App\Models\TokenUsage;
use App\Services\Base\BaseService;
use App\Services\Membership\ReferralService;
use App\Services\Product\ProductService;
use App\Services\User\UserProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
    protected UserProfileService $profile;
    protected ReferralService $referral;
    protected ProductService $product;
    public function __construct(
        UserProfileService $profile,
        ReferralService $referral,
        ProductService $product
    ){
        $this->profile = $profile;
        $this->referral = $referral;
        $this->product = $product;
    }

    public function userRegistration($request): array
    {
        $inputs = $request->all();
        $inputs['email_token'] = BaseService::randomCharacters(6, '0123456789');

        DB::beginTransaction();
        try{
            $inputs['referred_by'] = !empty($inputs['referred_by_code']) ? $this->getReferrerId($inputs['referred_by_code']) : null;

            $user = $this->profile->user()->firstOrCreate([
                'email' => $inputs['email']
            ], [
                'email' => $inputs['email'],
                'referred_by' => $inputs['referred_by'],
            ]);

            if(!empty($inputs['referred_by'])){
                $this->referral->addReferrerToReceiver($inputs['referred_by'], $user->id);
            }

            if(!empty($inputs['membership'])) {
                $this->product->addProductToUser($inputs['membership'], $user->id);
            }

            TokenUsage::create([
                'key' => $inputs['email_token'],
                'user_id' => $user->id,
                'target_id' => $user->id,
                'target_type' => 'registration',
                'model' => 'App\Models\User',
            ]);

            DB::commit();

        }catch(\Exception $e){
            DB::rollBack();
            BaseService::tryCatchException($e, $inputs['email']);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'server_error' => 'Server Error, Please try again later'
            ];
        }

        // For queue event listener user registration
        $eventData['user_id'] = $user->id;
        $eventData['email'] = $user->email;
        $eventData['email_token'] = $inputs['email_token'];

        // Send email to registered user using event listener
        // Will add queue later
        event(new UserRegistrationEvent($eventData));

        return [
            'success' => true,
            'message' => 'Sign On Complete, verify Email',
            'user' => new UserResource($user)
        ];
    }

    public function userEmailVerification($request): array
    {
        $inputs = $request->all();

        $tokenUsage = TokenUsage::where([
            'key' => $inputs['email_token'],
            'target_type' => 'registration',
            'model' => 'App\Models\User'
        ])->first();

        if(!$tokenUsage){
            return [
                'success' => false,
                'message' => 'Invalid Token',
                'errors' => ['email_token' => ['Invalid Token']],
                'status_code' => 422
            ];
        }

        $user = $this->profile->user()->where('id', $tokenUsage->user_id)->first();
        if(!$user){
            return [
                'success' => false,
                'message' => 'User not found',
                'errors' => ['email_token' => ['No user associated with this token']],
                'status_code' => 422
            ];
        }

        $checkReferredByCode = $this->profile->user()->where('referral_code', $inputs['referred_by_code'])->first();
        if(!empty($inputs['referred_by_code']) && !$checkReferredByCode){
            return [
                'success' => false,
                'message' => 'Invalid Referral Code',
                'errors' => ['referred_by_code' => ['Invalid Referral Code']],
                'status_code' => 422
            ];
        }

        DB::beginTransaction();
        try {
            if(!$user->is_verified){
                $user->email_verified_at = now()->format('Y-m-d H:i:s');
                $user->is_verified = true;
                $user->referred_by = !empty($inputs['referred_by_code']) ? $this->getReferrerId($inputs['referred_by_code']) : null;
                $user->referral_code = BaseService::randomCharacters(8, 'abcdefghijklmnop0123456789');

                // Add referral to user
                if(!empty($inputs['referred_by_code'])){
                    $this->referral->addReferrerToReceiver($user->referred_by, $user->id);
                }
            }

            $user->last_login = now()->format('Y-m-d H:i:s');
            $user->save();

            $tokenUsage->is_active = false;
            $tokenUsage->save();

            DB::commit();

            Auth::login($user);
            $request->session()->regenerate();

            return [
                'success' => true,
                'message' => 'Email Verified',
                'user' => new UserResource($user)
            ];

        } catch (\Exception $e){

            DB::rollBack();
            BaseService::tryCatchException($e, $user->id);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'server_error' => 'Server Error, Please try again later'
            ];
        }
    }

    private function getReferrerId($referredByCode)
    {
        return $this->profile->user()->where('referral_code', $referredByCode)->first()?->id;
    }

}
