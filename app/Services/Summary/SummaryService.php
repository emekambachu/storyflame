<?php

namespace App\Services\Summary;

use App\Http\Resources\Admin\Summary\AdminSummaryResource;
use App\Models\DataPoint\DataPointSummary;
use App\Models\Summary\Summary;
use App\Models\Summary\SummaryCategory;
use App\Models\Summary\SummaryLink;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SummaryService
{
    public function summary(): Summary
    {
        return new Summary();
    }

    public function summaryCategory(): SummaryCategory
    {
        return new SummaryCategory();
    }

    public function dataPointSummary(): DataPointSummary
    {
        return new DataPointSummary();
    }

    public function summaryLink(): SummaryLink
    {
        return new SummaryLink();
    }

    public function storeSummary($request): array
    {
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            $inputs['user_id'] = Auth::id();
            $inputs['slug'] = Str::slug($inputs['name']).BaseService::randomCharacters(10, '0123456789ABCDEFGH');
            $summary = $this->summary()->create($inputs);

            if(!empty($inputs['categories'])){
                foreach ($inputs['categories'] as $categoryId){
                    $this->summaryCategory()->create([
                        'summary_id' => $summary->id,
                        'category_id' => $categoryId,
                    ]);
                }
            }

            if(!empty($inputs['data_points'])){
                foreach ($inputs['data_points'] as $dataPointId){
                    $this->dataPointSummary()->create([
                        'summary_id' => $summary->id,
                        'data_point_id' => $dataPointId,
                    ]);
                }
            }

            if(!empty($inputs['summaries'])){
                foreach ($inputs['summaries'] as $summaryId){
                    $this->summaryLink()->create([
                        'summary_id' => $summary->id,
                        'linked_summary_id' => $summaryId,
                    ]);
                }
            }

            DB::commit();
            return [
                'success' => true,
                'summary' => new AdminSummaryResource($summary)
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

    public function updateSummary($request, $id): array
    {
        $summary = $this->summary()->where('id', $id)->first();

        DB::beginTransaction();
        try {
            $inputs = $request->all();

            $inputs['user_id'] = Auth::id();
            $inputs['slug'] = Str::slug($inputs['name']).BaseService::randomCharacters(10, '0123456789ABCDEFGH');
            $summary->update($inputs);

            if(!empty($inputs['categories'])){

                $existingCategories = $this->summaryCategory()->where('summary_id', $summary->id)->get();
                // remove deleted categories
                if($existingCategories->count() > 0){
                    foreach ($existingCategories as $category){
                        if(!in_array($category->category_id, $inputs['categories'], true)){
                            $category->delete();
                        }
                    }
                }

                $existingCategoriesIds = $existingCategories->pluck('category_id')->toArray();

                foreach ($inputs['categories'] as $categoryId){
                    if(!in_array($categoryId, $existingCategoriesIds, true)){
                        $this->summaryCategory()->create([
                            'summary_id' => $summary->id,
                            'category_id' => $categoryId,
                        ]);
                    }
                }
            }


            if(!empty($inputs['data_points'])){

                $existingDataPoints = $this->dataPointSummary()->where('summary_id', $summary->id)->get();
                // remove deleted categories
                if($existingDataPoints->count() > 0){
                    foreach ($existingDataPoints as $dataPoint){
                        if(!in_array($dataPoint->id, $inputs['data_points'], true)){
                            $dataPoint->delete();
                        }
                    }
                }

                $existingDataPointIds = $existingDataPoints->pluck('data_point_id')->toArray();

                foreach ($inputs['data_points'] as $dataPointId){
                    if(!in_array($dataPointId, $existingDataPointIds, true)){
                        $this->dataPointSummary()->create([
                            'data_point_id' => $dataPointId,
                            'summary_id' => $summary->id,
                        ]);
                    }
                }

            }

            if(!empty($inputs['summaries'])){

                $existingSummaryLinks = $this->summaryLink()->where('summary_id', $summary->id)->get();
                // remove deleted categories
                if($existingSummaryLinks->count() > 0){
                    foreach ($existingSummaryLinks as $link){
                        if(!in_array($link->linked_summary_id, $inputs['summaries'], true)){
                            $link->delete();
                        }
                    }
                }

                $existingSummaryLinksIds = $existingSummaryLinks->pluck('linked_summary_id')->toArray();

                foreach ($inputs['summaries'] as $summaryId){
                    if(!in_array($summaryId, $existingSummaryLinksIds, true)){
                        $this->summaryLink()->create([
                            'summary_id' => $summary->id,
                            'linked_summary_id' => $summaryId,
                        ]);
                    }
                }

            }

            DB::commit();
            return [
                'success' => true,
                'summary' => new AdminSummaryResource($summary)
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

    public function deleteSummary($id): array
    {
        // Start a transaction
        DB::beginTransaction();
        try {
            $summary = $this->summary()->where('id', $id)->first();
            // Check if the achievement exists
            if (!$summary) {
                return [
                    'success' => false,
                    'error_message' => 'Summary Not Found'
                ];
            }

            // Detach the categories
            if ($summary->categories && $summary->categories->count() > 0) {
                $summary->categories()->detach();
            }

            // Detach the achievements
            if ($summary->dataPoints && $summary->dataPoints->count() > 0) {
                $summary->dataPoints()->detach();
            }

            // Detach the summaries
            if ($summary->summaries && $summary->summaries->count() > 0) {
                $summary->summaries()->detach();
            }

            // Delete the achievement
            $summary->delete();

            // Commit the transaction
            DB::commit();

            return [
                'success' => true,
                'message' => 'Summary Deleted Successfully'
            ];

        } catch (\Exception $e) {
            // Rollback the transaction in case of errors
            DB::rollBack();
            // Log the error
            BaseService::logError($e);

            return [
                'success' => false,
                'error_message' => 'An error occurred while deleting the summary'
            ];
        }
    }
}
