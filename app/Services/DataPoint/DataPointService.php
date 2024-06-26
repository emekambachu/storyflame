<?php

namespace App\Services\DataPoint;

use App\Http\Resources\Admin\Achievement\AdminAchievementResource;
use App\Http\Resources\Admin\DataPoint\AdminDataPointResource;
use App\Models\Achievement;
use App\Models\Achievement\AchievementCategory;
use App\Models\DataPoint;
use App\Models\DataPoint\DataPointAchievement;
use App\Models\DataPoint\DataPointCategory;
use App\Models\DataPoint\DataPointSummary;
use App\Models\Summary\SummaryLink;
use App\Services\Base\BaseService;
use App\Services\Base\CrudService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataPointService
{
    public function dataPoint(): DataPoint
    {
        return new DataPoint();
    }

    public function dataPointAchievement(): DataPointAchievement
    {
        return new DataPointAchievement();
    }

    public function dataPointCategory(): DataPointCategory
    {
        return new DataPointCategory();
    }

    public function dataPointSummary(): DataPointSummary
    {
        return new DataPointSummary();
    }

    public function storeDataPoint($request): array
    {
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            $inputs['item_id'] = BaseService::randomCharacters(5, '0123456789');
            $inputs['admin_id'] = Auth::id();
            $inputs['slug'] = Str::slug($inputs['name']).BaseService::randomCharacters(10, '0123456789ABCDEFGH');
            $dataPoint = $this->dataPoint()->create($inputs);

            if(!empty($inputs['categories'])){
                foreach ($inputs['categories'] as $categoryId){
                    $this->dataPointCategory()->create([
                        'data_point_id' => $dataPoint->id,
                        'category_id' => $categoryId,
                    ]);
                }
            }

            if(!empty($inputs['achievements'])){
                foreach ($inputs['achievements'] as $achievementId){
                    $this->dataPointAchievement()->create([
                        'data_point_id' => $dataPoint->id,
                        'achievement_id' => $achievementId,
                    ]);
                }
            }

            if(!empty($inputs['summaries'])){
                foreach ($inputs['summaries'] as $summaryId){
                    $this->dataPointSummary()->create([
                        'data_point_id' => $dataPoint->id,
                        'summary_id' => $summaryId,
                    ]);
                }
            }

            DB::commit();
            return [
                'success' => true,
                'data_point' => new AdminDataPointResource($dataPoint)
            ];

        }catch (\Exception $e){
            DB::rollBack();
            BaseService::logError($e);
            return [
                'success' => false,
                'error_message' => 'Something went wrong',
                'server_error' => $e->getMessage(),
                'status_code' => 500
            ];
        }
    }

    public function updateDataPoint($request): array
    {
        $dataPoint = $this->dataPoint()->where('item_id', $request->item_id)->first();

        DB::beginTransaction();
        try {
            $inputs = $request->all();

            $inputs['admin_id'] = Auth::id();
            $inputs['slug'] = Str::slug($inputs['name']).BaseService::randomCharacters(10, '0123456789ABCDEFGH');
            $dataPoint->update($inputs);

            if(!empty($inputs['categories'])){

                $existingCategories = $this->dataPointCategory()->where('data_point_id', $dataPoint->id)->get();
                // remove deleted categories
                foreach ($existingCategories as $category){
                    if(!in_array($category->category_id, $inputs['categories'], true)){
                        $category->delete();
                    }
                }

                $existingCategoriesIds = $existingCategories->pluck('category_id')->toArray();

                foreach ($inputs['categories'] as $categoryId){
                    if(!in_array($categoryId, $existingCategoriesIds, true)){
                        $this->dataPointCategory()->create([
                            'category_id' => $categoryId,
                            'data_point_id' => $dataPoint->id,
                        ]);
                    }
                }

            }

            if(!empty($inputs['achievements'])){

                $existingAchievements = $this->dataPointAchievement()->where('data_point_id', $dataPoint->id)->get();
                // remove deleted categories
                if($existingAchievements->count() > 0){
                    foreach ($existingAchievements as $achievement){
                        if(!in_array($achievement->achievement_id, $inputs['achievements'], true)){
                            $achievement->delete();
                        }
                    }
                }

                $existingAchievementsIds = $existingAchievements->pluck('achievement_id')->toArray();

                foreach ($inputs['achievements'] as $achievementId){
                    if(!in_array($achievementId, $existingAchievementsIds, true)){
                        $this->dataPointAchievement()->create([
                            'achievement_id' => $achievementId,
                            'data_point_id' => $dataPoint->id,
                        ]);
                    }
                }

            }

            if(!empty($inputs['summaries'])){

                $existingSummaries = $this->dataPointSummary()->where('data_point_id', $dataPoint->id)->get();
                // remove deleted categories
                if($existingSummaries->count() > 0){
                    foreach ($existingSummaries as $summary){
                        if(!in_array($summary->summary_id, $inputs['summaries'], true)){
                            $summary->delete();
                        }
                    }
                }

                $existingSummaryIds = $existingSummaries->pluck('summary_id')->toArray();

                foreach ($inputs['summaries'] as $summaryId){
                    if(!in_array($summaryId, $existingSummaryIds, true)){
                        $this->dataPointSummary()->create([
                            'summary_id' => $summaryId,
                            'data_point_id' => $dataPoint->id,
                        ]);
                    }
                }

            }

            DB::commit();
            return [
                'success' => true,
                'data_points' => new AdminDataPointResource($dataPoint)
            ];

        }catch (\Exception $e){
            DB::rollBack();
            BaseService::logError($e);
            return [
                'success' => false,
                'error_message' => 'Something went wrong',
                'server_error' => $e->getMessage(),
                'status_code' => 500
            ];
        }
    }

    public function deleteDataPoint($request): array
    {
        // Start a transaction
        DB::beginTransaction();
        try {
            $dataPoint = $this->dataPoint()->where('item_id', $request->item_id)->first();
            // Check if the achievement exists
            if (!$dataPoint) {
                return [
                    'success' => false,
                    'error_message' => 'Achievement not found'
                ];
            }

            // Detach the categories
            if ($dataPoint->categories && $dataPoint->categories->count() > 0) {
                $dataPoint->categories()->detach();
            }

            // Detach the achievements
            if ($dataPoint->achievements && $dataPoint->achievements->count() > 0) {
                $dataPoint->achievements()->detach();
            }

            // Detach the summaries
            if ($dataPoint->summaries && $dataPoint->summaries->count() > 0) {
                $dataPoint->summaries()->detach();
            }

            // Delete the achievement
            $dataPoint->delete();

            // Commit the transaction
            DB::commit();

            return [
                'success' => true,
                'message' => 'Achievement deleted successfully'
            ];

        } catch (\Exception $e) {
            // Rollback the transaction in case of errors
            DB::rollBack();
            // Log the error
            BaseService::logError($e);

            return [
                'success' => false,
                'error_message' => 'An error occurred while deleting the achievement'
            ];
        }
    }
}
