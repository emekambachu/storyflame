<?php

namespace App\Services\Membership;

use App\Models\Product\UserProduct;
use App\Models\ProductPrice;
use App\Models\Referral\ReferralType;
use App\Models\Referral\UserDiscount;
use App\Models\Referral\UserReferral;
use App\Models\Referral\UserReferralType;
use App\Models\User;
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

    public function userProduct(): UserProduct
    {
        return new UserProduct();
    }

    public function productPrice(): ProductPrice
    {
        return new ProductPrice();
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

//        $referralType = $referredByType->name;
//        $receiverUser = $this->user()->find($userId);

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
        $currentDate = now();
        $firstDeadline = Carbon::createFromDate(2024, 8, 15);

        $discount = 5;
        $discountDuration = 1;

        if ($currentDate <= $firstDeadline) {
            $discount = 10;
        }

        UserDiscount::create([
            'referrer_id' => $referrerId,
            'recipient_id' => $receiverId,
            'amount' => $discount,
            'type' => 'percentage',
            'percentage' => $discount,
            'discount_ends_at' => $currentDate->addMonths($discountDuration),
        ]);
    }

    private function applyAffiliateProgram($referrerId, $receiverId): void
    {
        $currentDate = now();
        $augustFifteenth = $currentDate->copy()->setDate($currentDate->year, 8, 15);
        $septemberFifteenth = $currentDate->copy()->setDate($currentDate->year, 9, 15);

        if ($currentDate >= $augustFifteenth && $currentDate <= $septemberFifteenth) {
            $this->userReferral()
                ->where('referrer_id', $referrerId)
                ->where('receiver_id', $receiverId)
                ->update([
                    'commission_percentage' => 5,
                    'commission_duration' => 1,
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
        $currentDate = now();
        $augustFifteenth = $currentDate->copy()->setDate($currentDate->year, 8, 15);
        $septemberFifteenth = $currentDate->copy()->setDate($currentDate->year, 9, 15);

        if ($currentDate >= $augustFifteenth && $currentDate <= $septemberFifteenth) {
            $this->userReferral()
                ->where('referrer_id', $referrerId)
                ->where('receiver_id', $receiverId)
                ->update([
                    'commission_percentage' => 7,
                    'commission_duration' => 1,
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

    private function applyStandardAffiliate($receiverUser): void
    {
        $receiverUser->update([
            'first_purchase_discount' => 10,
        ]);
    }

}
