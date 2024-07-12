<?php

namespace App\Services\Auth;

use App\Events\UserRegistrationEvent;
use App\Http\Resources\UserResource;
use App\Models\TokenUsage;
use App\Services\Base\BaseService;
use App\Services\Membership\ReferralService;
use App\Services\Product\ProductService;
use App\Services\User\UserProfileService;
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
        $inputs['referral_code'] = BaseService::randomCharacters(8, 'abcdefghijklmnop0123456789');

        DB::beginTransaction();
        try{
            $inputs['referred_by'] = !empty($inputs['referred_by_code']) ? $this->getReferrerId($inputs['referred_by_code']) : null;
            $user = $this->profile->user()->create($inputs);

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
            'message' => 'Registration Complete',
            'user' => new UserResource($user)
        ];
    }

    public function userEmailVerification($token): array
    {
        $tokenUsage = TokenUsage::where([
            'key' => $token,
            'target_type' => 'registration',
            'model' => 'App\Models\User'
        ])->first();

        if(!$tokenUsage){
            return [
                'success' => false,
                'message' => 'Invalid Token',
                'errors' => ['token' => ['Invalid Token']]
            ];
        }

        $user = $this->profile->user()->where('id', $tokenUsage->user_id)->first();
        if(!$user){
            return [
                'success' => false,
                'message' => 'User not found',
                'errors' => ['token' => ['No user associated with this token']]
            ];
        }

        DB::beginTransaction();
        try {

            $user->email_verified_at = now()->format('Y-m-d H:i:s');
            $user->save();

            $tokenUsage->is_active = false;
            $tokenUsage->save();

            DB::commit();

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
