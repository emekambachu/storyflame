<?php

namespace App\Jobs;

use App\Models\AIImageSlot;
use App\Models\UserDevelopmentReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpireRecords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = now();

        try {
            DB::transaction(function () use ($now) {
                // Expire AI Image Slots
                $expiredAISlots = AIImageSlot::where('expires_at', '<', $now)
                    ->where('status', '!=', AIImageSlot::STATUS_EXPIRED)
                    ->update([
                        'status' => AIImageSlot::STATUS_EXPIRED,
                        'updated_at' => $now
                    ]);

                // Expire User Development Reports
                $expiredReports = UserDevelopmentReport::where('expires_at', '<', $now)
                    ->where('status', '!=', UserDevelopmentReport::STATUS_EXPIRED)
                    ->update([
                        'status' => UserDevelopmentReport::STATUS_EXPIRED,
                        'updated_at' => $now
                    ]);

                Log::info("Expired AI Image Slots: " . $expiredAISlots);
                Log::info("Expired User Development Reports: " . $expiredReports);
            });
        } catch (\Exception $e) {
            // Log the error
            Log::error($e->getMessage());
        }
    }
}
