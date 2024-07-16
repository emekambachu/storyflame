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
            // Get active and unexpired reports from the old transaction
            $activeReports = $oldTransaction->userDevelopmentReports()
                ->where('status', UserDevelopmentReport::STATUS_AVAILABLE)
                ->where('expires_at', '>', now())
                ->orderBy('created_at')
                ->get();

            $usedReportsCount = $oldReportsCount - $activeReports->count();
            $allowedReportsCount = $newReportsCount - $usedReportsCount;

            // Prorate all existing reports
            $activeReports->each(function ($report) {
                $report->update([
                    'status' => UserDevelopmentReport::STATUS_PRORATED,
                    'expires_at' => now(),
                ]);
            });

            // Create new reports
            $this->createUserDevelopmentReports($allowedReportsCount);

            // If we created more reports than allowed, prorate the excess
            $excessReports = $this->userDevelopmentReports()
                ->where('status', UserDevelopmentReport::STATUS_AVAILABLE)
                ->orderBy('created_at', 'desc')
                ->limit(max(0, $allowedReportsCount - $newReportsCount))
                ->get();

            $excessReports->each(function ($report) {
                $report->update([
                    'status' => UserDevelopmentReport::STATUS_PRORATED,
                    'expires_at' => now(),
                ]);
            });

            Log::info("Successfully prorated user development reports", [
                'transaction_id' => $this->paddle_id,
                'old_count' => $oldReportsCount,
                'new_count' => $newReportsCount,
                'active_reports' => $activeReports->count(),
                'used_reports' => $usedReportsCount,
                'allowed_reports' => $allowedReportsCount,
                'prorated_old' => $activeReports->count(),
                'prorated_new' => $excessReports->count(),
                'created' => $allowedReportsCount,
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
