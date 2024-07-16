<?php

namespace App\Services\Referral;

use App\Models\Discount\DiscountTally;
use App\Models\Product\UserProduct;
use App\Models\ProductPrice;
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

    public function productPrice(): ProductPrice
    {
        return new ProductPrice();
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

        $this->userReferral()->create([
            'referrer_id' => $referredById,
            'receiver_id' => $userId,
            'referral_type_id' => $referredByType->id,
        ]);

        if ($referredByType->slug === 'beta-testers') {
            $this->applyBetaTesterReferral($referredById, $userId);
        } elseif ($referredByType->slug === 'affiliate-program') {
            $this->applyAffiliateProgram($referredById, $userId);
        } elseif ($referredByType->slug === 'internal-team-affiliate-program') {
            $this->applyInternalTeamAffiliateProgram($referredById, $userId);
        } elseif ($referredByType->slug === 'standard-affiliate-link') {
            $this->applyStandardAffiliate($userId);
        }
    }

    private function applyBetaTesterReferral($referrerId, $receiverId): void
    {
        $currentDate = Carbon::now();
        $augustFifteenth = Carbon::createFromDate($currentDate->year, 8, 15);

        if ($currentDate <= $augustFifteenth) {
            $discount = 10;
            $discountDuration = 1;

            // Apply discount to the referrer
            DiscountTally::create([
                'user_id' => $referrerId,
                'recipient_id' => $receiverId,
                'amount' => $discount,
                'type' => 'percentage',
                'percentage' => $discount,
                'discount_ends_at' => $currentDate->addMonths($discountDuration),
            ]);

            // Apply discount to the receiver
            DiscountTally::create([
                'user_id' => $receiverId,
                'recipient_id' => $referrerId,
                'amount' => $discount,
                'type' => 'percentage',
                'percentage' => $discount,
                'discount_ends_at' => $currentDate->addMonths($discountDuration),
            ]);

        } else {
            $discount = 5;

            UserDiscount::create([
                'referrer_id' => $referrerId,
                'recipient_id' => $receiverId,
                'amount' => $discount,
                'type' => 'percentage',
                'percentage' => $discount,
                'discount_ends_at' => null,
            ]);
        }
    }

    private function applyAffiliateProgram($referrerId, $receiverId): void
    {
        $currentDate = Carbon::now();
        $julyEleventh = Carbon::createFromDate($currentDate->year, 7, 11);
        $augustFifteenth = Carbon::createFromDate($currentDate->year, 8, 15);
        $septemberFifteenth = Carbon::createFromDate($currentDate->year, 9, 15);

        if ($currentDate >= $julyEleventh && $currentDate <= $augustFifteenth) {
            $this->userReferral()
                ->where('referrer_id', $referrerId)
                ->where('receiver_id', $receiverId)
                ->update([
                    'commission_percentage' => 5,
                    'commission_duration' => null,
                ]);
        } elseif ($currentDate > $augustFifteenth && $currentDate <= $septemberFifteenth) {
            $this->userReferral()
                ->where('referrer_id', $referrerId)
                ->where('receiver_id', $receiverId)
                ->update([
                    'commission_percentage' => 5,
                    'commission_duration' => 12,
                ]);
        } else {
            $this->userReferral()
                ->where('referrer_id', $referrerId)
                ->where('receiver_id', $receiverId)
                ->update([
                    'commission_percentage' => 5,
                    'commission_duration' => 0,
                ]);
        }
    }

    private function applyInternalTeamAffiliateProgram($referrerId, $receiverId): void
    {
        $currentDate = Carbon::now();
        $julyEleventh = Carbon::createFromDate($currentDate->year, 7, 11);
        $augustFifteenth = Carbon::createFromDate($currentDate->year, 8, 15);
        $septemberFifteenth = Carbon::createFromDate($currentDate->year, 9, 15);

        if ($currentDate >= $julyEleventh && $currentDate <= $augustFifteenth) {
            $this->userReferral()
                ->where('referrer_id', $referrerId)
                ->where('receiver_id', $receiverId)
                ->update([
                    'commission_percentage' => 7,
                    'commission_duration' => null,
                ]);
        } elseif ($currentDate > $augustFifteenth && $currentDate <= $septemberFifteenth) {
            $this->userReferral()
                ->where('referrer_id', $referrerId)
                ->where('receiver_id', $receiverId)
                ->update([
                    'commission_percentage' => 7,
                    'commission_duration' => 12,
                ]);
        } else {
            $this->userReferral()
                ->where('referrer_id', $referrerId)
                ->where('receiver_id', $receiverId)
                ->update([
                    'commission_percentage' => 7,
                    'commission_duration' => 0,
                ]);
        }
    }

    private function applyStandardAffiliate($receiverId): void
    {
        $discount = 10;

        UserDiscount::create([
            'recipient_id' => $receiverId,
            'amount' => $discount,
            'type' => 'percentage',
            'percentage' => $discount,
            'discount_ends_at' => null,
            'max_usage' => 1,
        ]);
    }

}
