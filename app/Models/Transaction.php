<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Transaction extends \Laravel\Paddle\Transaction
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'billable_id',
        'billable_type',
        'paddle_id',
        'paddle_subscription_id',
        'invoice_number',
        'status',
        'total',
        'tax',
        'currency',
        'billed_at',
        'discount',
        'fee',
        'earnings',
        'proration_rate',
    ];

    protected $dates = [
        'billed_at',
    ];

    /**
     * @return HasMany
     */
    public function aiImageSlots(): HasMany
    {
        return $this->hasMany(AIImageSlot::class);
    }

    public function createAIImageSlots(
        int $count,
        string $status = AIImageSlot::STATUS_AVAILABLE,
        int $daysToExpire = 366
    ): void {
        Log::info("Creating AI image slots", [
            'transaction_id' => $this->paddle_id,
            'count' => $count,
            'status' => $status,
            'days_to_expire' => $daysToExpire,
        ]);

        $expiresAt = now()->addDays($daysToExpire);

        $slots = array_fill(0, $count, [
            'user_id' => $this->billable_id,
            'transaction_id' => $this->id,
            'slot_status' => $status,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->aiImageSlots()->createMany($slots);

        Log::info("Created AI image slots", [
            'count' => $count,
            'transaction_id' => $this->paddle_id,
            'user_id' => $this->billable_id,
        ]);
    }

    public function prorateAIImageSlots(
        int $oldSlotsCount,
        int $newSlotsCount,
        float $prorationRate,
        Transaction $oldTransaction
    ): void {
        Log::info("Prorating AI image slots", [
            'transaction_id' => $this->paddle_id,
            'old_count' => $oldSlotsCount,
            'new_count' => $newSlotsCount,
            'proration_rate' => $prorationRate,
        ]);

        DB::transaction(function () use ($oldSlotsCount, $newSlotsCount, $prorationRate, $oldTransaction) {
            // 1. Get all slots from the previous transaction that are still active and not expired
            $activeSlots = $oldTransaction->aiImageSlots()
                ->where('slot_status', AIImageSlot::STATUS_AVAILABLE)
                ->where('expires_at', '>', now())
                ->orderBy('created_at')
                ->get();

            // 2. Get the count of available slots from the previous transaction
            $availableSlotsCount = $activeSlots->count();

            // 3. Get the count of processing || completed || failed slots from the previous transaction
            $usedSlotsCount = $oldTransaction->aiImageSlots()
                ->whereIn('slot_status', [
                    AIImageSlot::STATUS_PROCESSING,
                    AIImageSlot::STATUS_COMPLETED,
                    AIImageSlot::STATUS_FAILED
                ])
                ->count();

            // 4. Add the two counts
            $totalSlotsCount = $availableSlotsCount + $usedSlotsCount;

            // 5. Calculate how many slots should be kept available or made prorated
            $slotDifference = ceil($totalSlotsCount * $prorationRate) - $newSlotsCount;

            // 6. For negative numbers, find the oldest available slots and make them prorated
            $slotsToProrate = 0;
            if ($slotDifference < 0) {
                $slotsToProrate = min(abs($slotDifference), $availableSlotsCount);
                $activeSlots->take($slotsToProrate)->each(function ($slot) {
                    $slot->update([
                        'slot_status' => AIImageSlot::STATUS_PRORATED,
                        'expires_at' => now(),
                    ]);
                });
            }

            // 7. Create new slots, some of which may be prorated
            $newSlotsToCreate = $newSlotsCount - ($availableSlotsCount - $slotsToProrate);
            if ($newSlotsToCreate > 0) {
                $proratedNewSlots = max(0, $slotDifference);
                $availableNewSlots = $newSlotsToCreate - $proratedNewSlots;

                $this->createAIImageSlots($availableNewSlots);
                $this->createAIImageSlots($proratedNewSlots, AIImageSlot::STATUS_PRORATED);
            }

            Log::info("Successfully prorated AI image slots", [
                'transaction_id' => $this->paddle_id,
                'old_count' => $oldSlotsCount,
                'new_count' => $newSlotsCount,
                'active_slots' => $availableSlotsCount,
                'used_slots' => $usedSlotsCount,
                'total_slots' => $totalSlotsCount,
                'slot_difference' => $slotDifference,
                'prorated_old' => $slotsToProrate,
                'prorated_new' => $proratedNewSlots,
                'created_available' => $availableNewSlots,
                'created_prorated' => $proratedNewSlots,
            ]);
        });
    }

    /**
     * @return HasMany
     */
    public function userDevelopmentReports(): HasMany
    {
        return $this->hasMany(UserDevelopmentReport::class);
    }

    public function prorateUserDevelopmentReports(
        int $oldReportsCount,
        int $newReportsCount,
        float $prorationRate,
        Transaction $oldTransaction
    ): void {
        Log::info("Prorating user development reports", [
            'transaction_id' => $this->paddle_id,
            'old_count' => $oldReportsCount,
            'new_count' => $newReportsCount,
            'proration_rate' => $prorationRate,
        ]);

        DB::transaction(function () use ($oldReportsCount, $newReportsCount, $prorationRate, $oldTransaction) {
            // 1. Get all reports from the previous transaction that are still active and not expired
            $activeReports = $oldTransaction->userDevelopmentReports()
                ->where('status', UserDevelopmentReport::STATUS_AVAILABLE)
                ->where('expires_at', '>', now())
                ->orderBy('created_at')
                ->get();

            // 2. Get the count of available reports from the previous transaction
            $availableReportsCount = $activeReports->count();

            // 3. Get the count of processing || completed || failed reports from the previous transaction
            $usedReportsCount = $oldTransaction->userDevelopmentReports()
                ->whereIn('status', [
                    UserDevelopmentReport::STATUS_PROCESSING,
                    UserDevelopmentReport::STATUS_COMPLETED,
                    UserDevelopmentReport::STATUS_FAILED
                ])
                ->count();

            // 4. Add the two counts
            $totalReportsCount = $availableReportsCount + $usedReportsCount;

            // 5. Calculate how many reports should be kept available or made prorated
            $reportDifference = ceil($totalReportsCount * $prorationRate) - $newReportsCount;

            // 6. For negative numbers, find the oldest available reports and make them prorated
            if ($reportDifference < 0) {
                $reportsToProrate = min(abs($reportDifference), $availableReportsCount);
                $activeReports->take($reportsToProrate)->each(function ($report) {
                    $report->update([
                        'status' => UserDevelopmentReport::STATUS_PRORATED,
                        'expires_at' => now(),
                    ]);
                });
            }

            // 7. Create new reports, some of which may be prorated
            $newReportsToCreate = $newReportsCount - ($availableReportsCount - $reportsToProrate);
            if ($newReportsToCreate > 0) {
                $proratedNewReports = max(0, $reportDifference);
                $availableNewReports = $newReportsToCreate - $proratedNewReports;

                $this->createUserDevelopmentReports($availableNewReports);
                $this->createUserDevelopmentReports($proratedNewReports, UserDevelopmentReport::STATUS_PRORATED);
            }

            Log::info("Successfully prorated user development reports", [
                'transaction_id' => $this->paddle_id,
                'old_count' => $oldReportsCount,
                'new_count' => $newReportsCount,
                'active_reports' => $availableReportsCount,
                'used_reports' => $usedReportsCount,
                'total_reports' => $totalReportsCount,
                'report_difference' => $reportDifference,
                'prorated_old' => $reportsToProrate,
                'prorated_new' => $proratedNewReports,
                'created_available' => $availableNewReports,
                'created_prorated' => $proratedNewReports,
            ]);
        });
    }

    /**
     * @param int|null $count
     * @param string $status
     * @param int $daysToExpire
     * @return void
     */
    public function createUserDevelopmentReports(
        int $count,
        string $status = UserDevelopmentReport::STATUS_AVAILABLE,
        int $daysToExpire = UserDevelopmentReport::EXPIRES_IN_DAYS
    ): void {
        Log::info("Creating user development reports", [
            'transaction_id' => $this->paddle_id,
            'count' => $count,
            'status' => $status,
            'days_to_expire' => $daysToExpire,
        ]);

        $expiresAt = now()->addDays($daysToExpire);

        $reports = array_fill(0, $count, [
            'user_id' => $this->billable_id,
            'transaction_id' => $this->id,
            'status' => $status,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->userDevelopmentReports()->createMany($reports);

        Log::info("Created user development reports", [
            'count' => $count,
            'transaction_id' => $this->paddle_id,
            'user_id' => $this->billable_id,
        ]);
    }
}
