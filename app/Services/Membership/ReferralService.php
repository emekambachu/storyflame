<?php

namespace App\Services\Membership;

use App\Models\Referral\ReferralType;
use Illuminate\Support\Str;

class ReferralService
{
    public function referralTypes(): ReferralType
    {
        return new ReferralType();
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

}
