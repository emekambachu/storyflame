<?php

namespace App\Services\Auth;

use App\Events\UserRegistrationEvent;
use App\Services\Base\BaseService;
use App\Services\User\UserProfileService;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    protected UserProfileService $profile;
    public function __construct(UserProfileService $profile){
        $this->profile = $profile;
    }

    public function userRegistration($request): array
    {
        $inputs = $request->all();
        $inputs['email_token'] = BaseService::randomCharacters(11, 'abcdefghijklmnop0123456789');
        $inputs['referral_code'] = BaseService::randomCharacters(8, 'abcdefghijklmnop0123456789');

        //$inputs['referred_by_id'] = !empty($inputs['referrer_code']) ? $inputs['referrer_code'] : null;

        DB::beginTransaction();

        try{
            $user = $this->profile->user()->create($inputs);
            // Assign referral type
            // Assign Membership type

            DB::commit();

        }catch(\Exception $e){
            DB::rollBack();
            BaseService::tryCatchException($e, $inputs['email']);

            return [
                'success' => false,
                'message' => 'Server Error, Please try again later or contact membership@afchub.org'
            ];
        }

        // For queue event listener user registration
        $eventData['user_id'] = $user->id;;
        $eventData['title'] = "Update your profile";
        $eventData['name'] = $user->name;
        $eventData['email'] = $user->email;
        $eventData['email_token'] = $inputs['email_token'];

        // For queue event listener
//        MembershipSignUpEvent::dispatch($eventData);

        // For only event listener
        event(new UserRegistrationEvent($eventData));

        return [
            'success' => true,
            'message' => 'Registration Complete'
        ];
    }

}
