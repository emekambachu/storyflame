<?php

namespace App\Services\Referral;

use App\Models\Referral\ReferralReward;
use App\Models\Referral\ReferralType;
use App\Models\Referral\UserReferralType;
use App\Models\User;
use App\Services\Base\BaseService;
use Illuminate\Support\Str;

class ReferralService
{
    public function referralTypes(): ReferralType
    {
        return new ReferralType();
    }

    public function user(): User
    {
        return new User();
    }

    public function userReferralType(): UserReferralType
    {
        return new UserReferralType();
    }

    public function referralReward(): ReferralReward
    {
        return new ReferralReward();
    }

    public function generateUniqueReferralCode(): string
    {
        $referralCode = BaseService::randomCharacters(8, 'abcdefghijklmnopgrst0123456789');
        $referralCodeExists = $this->user()->where('referral_code', $referralCode)->exists();

        if ($referralCodeExists) {
            return $this->generateUniqueReferralCode();
        }

        return $referralCode;
    }

    public function storeReferralType($request): array
    {
        $inputs = $request->all();
        $inputs['slug'] = Str::Slug($inputs['name']);
        $referralType = $this->referralTypes()->create($inputs);

        return [
            'success' => true,
            'referral_type' => $referralType,
            'message' => 'Referral Type created successfully'
        ];
    }

    public function updateReferralType($request, $slug): array
    {
        $inputs = $request->all();
        $inputs['slug'] = Str::Slug($inputs['name']);
        $referralType = $this->referralTypes()->create($inputs);

        return [
            'success' => true,
            'referral_type' => $referralType,
            'message' => 'Referral Type created successfully'
        ];
    }

    public function deleteReferralType($slug): array
    {
        $referralType = $this->referralTypes()->where('slug', $slug)->first();
        $referralType->delete();

        return [
            'success' => true,
            'message' => 'Referral Type deleted successfully'
        ];
    }

    public function addReferrerToReceiver($referredById, $userId): void
    {
        $referredByType = $this->user()
            ->with('referral_types')
            ->find($referredById)?->referral_types
            ->orderBy('priority', 'asc')->first();

        if (!$referredByType) {
            return;
        }

        // Store only Ids
        $this->referralReward()->create([
            'referrer_id' => $referredById,
            'recipient_id' => $userId,
            'referral_type_id' => $referredByType->id,
        ]);
    }

}
