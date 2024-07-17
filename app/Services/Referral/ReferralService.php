<?php

namespace App\Services\Referral;

use App\Models\Discount\DiscountTally;
use App\Models\Product\UserProduct;
use App\Models\ProductPrice;
use App\Models\Referral\ReferralReward;
use App\Models\Referral\ReferralType;
use App\Models\Referral\UserDiscount;
use App\Models\Referral\UserReferral;
use App\Models\Referral\UserReferralType;
use App\Models\User;
use App\Services\Base\BaseService;
use Carbon\Carbon;
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

    public function userReferral(): UserReferral
    {
        return new UserReferral();
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

        if ($referredByType->slug === 'beta-testers') {
            $this->applyBetaTesterReferral($referredById, $userId);
        } elseif ($referredByType->slug === 'affiliate-program') {
            $this->applyAffiliateProgram($referredById, $userId);
        } elseif ($referredByType->slug === 'internal-team-affiliate-program') {
            $this->applyInternalTeamAffiliateProgram($referredById, $userId);
        } elseif ($referredByType->slug === 'standard-affiliate-link') {
            $this->applyStandardAffiliate($referredById, $userId);
        }
    }

    private function applyBetaTesterReferral($referrerId, $receiverId): void
    {
        $currentDate = Carbon::now();
        // 15th of August
        $targetDate = Carbon::createFromDate($currentDate->year, 8, 15);

        if ($currentDate <= $targetDate) {
            $discount = 10;
        } else {
            $discount = 5;
        }
        $this->referralReward()->create([
             'referrer_id' => $referrerId,
             'recipient_id' => $receiverId,
             'reward_type' => 'discount',
             'reward_percentage' => $discount,
             'reward_starts_at' => $currentDate,
         ]);
    }

    private function applyAffiliateProgram($referrerId, $receiverId): void
    {
        $currentDate = Carbon::now();
        $julyEleventh = Carbon::createFromDate($currentDate->year, 7, 11);
        $augustFifteenth = Carbon::createFromDate($currentDate->year, 8, 15);
        $septemberFifteenth = Carbon::createFromDate($currentDate->year, 9, 15);

        $commission = 5;
        if ($currentDate >= $julyEleventh && $currentDate <= $augustFifteenth) {
            $duration = null;
        } elseif ($currentDate > $augustFifteenth && $currentDate <= $septemberFifteenth) {
            $duration = 12;
        } else {
            $duration = 0;
        }

        $this->referralReward()->create([
            'referrer_id' => $referrerId,
            'recipient_id' => $receiverId,
            'reward_type' => 'commission',
            'reward_percentage' => $commission,
            'reward_duration' => $duration,
            'reward_starts_at' => $currentDate,
        ]);
    }

    private function applyInternalTeamAffiliateProgram($referrerId, $receiverId): void
    {
        $currentDate = Carbon::now();
        $julyEleventh = Carbon::createFromDate($currentDate->year, 7, 11);
        $augustFifteenth = Carbon::createFromDate($currentDate->year, 8, 15);
        $septemberFifteenth = Carbon::createFromDate($currentDate->year, 9, 15);

        $commission = 7;
        if ($currentDate >= $julyEleventh && $currentDate <= $augustFifteenth) {
            $duration = null;
        } elseif ($currentDate > $augustFifteenth && $currentDate <= $septemberFifteenth) {
            $duration = 12;
        } else {
            $duration = 0;
        }

        $this->referralReward()->create([
            'referrer_id' => $referrerId,
            'recipient_id' => $receiverId,
            'reward_type' => 'commission',
            'reward_percentage' => $commission,
            'reward_duration' => $duration,
            'reward_starts_at' => $currentDate,
        ]);
    }

    private function applyStandardAffiliate($referrerId, $receiverId): void
    {
        $discount = 10;
        $currentDate = Carbon::now();

        $this->referralReward()->create([
            'referrer_id' => $referrerId,
            'recipient_id' => $receiverId,
            'reward_type' => 'discount',
            'reward_percentage' => $discount,
            'reward_starts_at' => $currentDate,
            'reward_ends_at' => null,
            'reward_max_usage' => 1,
        ]);
    }

}
