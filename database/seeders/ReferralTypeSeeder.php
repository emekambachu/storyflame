<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReferralTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $referralTypes = [
            [
                'name' => 'Beta Testers',
                'slug' => 'beta-testers',
                'priority' => 5,
                'is_active' => true,
                'description' => '
                    Name: Beta Tester Referral
                    Feature:
                    Until August 15
                    Beta Tester gets 10% off their membership (down to $2/month or $20/year)
                    Recipient gets 10% off their first purchase
                    After August 15
                    Each affiliate sign-up reduces your membership 5% down to $2/month as long as they are signed up
                    Recipient gets 10% off their first purchase',
            ],
            [
                'name' => 'Affiliate Program',
                'slug' => 'affiliate-program',
                'priority' => 9,
                'is_active' => true,
                'description' => 'Between July 11 - August 15
                    5% commission in perpetuity (never ends)
                    Recipient gets 10% off their first purchase
                    Between August 15 - September 15
                    5% commission for the first year
                    Recipient gets 10% off their first purchase
                    After September 15
                    5% commission on the first purchase',
            ],
            [
                'name' => 'Internal Team Affiliate Program',
                'slug' => 'internal-team-affiliate-program',
                'priority' => 10,
                'is_active' => true,
                'description' => 'Referral Type 3 Description',
            ],
            [
                'name' => 'Standard Affiliate Link',
                'slug' => 'standard-affiliate-link',
                'priority' => 1,
                'is_active' => true,
                'description' => 'Referral Type 3 Description',
            ],

        ];
    }
}
