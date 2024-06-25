<?php

namespace App\Services;

use App\Http\Resources\AchievementResource;
use App\Http\Resources\Admin\Achievement\AdminAchievementResource;
use App\Models\Achievement;
use App\Models\Achievement\AchievementCategory;
use App\Models\DataPoint;
use App\Models\DataPoint\DataPointAchievement;
use App\Models\User;
use App\Models\UserAchievement;
use App\Services\Base\BaseService;
use App\Services\Base\CrudService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AchievementService
{
    protected string $imagePath = 'uploads/achievements/icons';

    public function achievement(): Achievement
    {
        return new Achievement();
    }

    public function achievementCategory(): AchievementCategory
    {
        return new AchievementCategory();
    }

    public function dataPoint(): DataPoint
    {
        return new DataPoint();
    }

    public function dataPointAchievement(): DataPointAchievement
    {
        return new DataPointAchievement();
    }

    public function storeAchievement($request): array
    {
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            $inputs['icon'] = CrudService::uploadAndCompressImage($request, $this->imagePath, null, null, 'icon');
            $inputs['icon_path'] = '/'.$this->imagePath.'/';
            $inputs['item_id'] = BaseService::randomCharacters(5, '0123456789');
            $inputs['admin_id'] = Auth::id();
            $inputs['slug'] = Str::slug($inputs['name']).BaseService::randomCharacters(10, '0123456789ABCDEFGH');
            $achievement = $this->achievement()->create($inputs);

            if(!empty($inputs['categories'])){
                foreach ($inputs['categories'] as $categoryId){
                    $this->achievementCategory()->create([
                        'category_id' => $categoryId,
                        'achievement_id' => $achievement->id,
                    ]);
                }
            }

            if(!empty($inputs['data_points'])){
                foreach ($inputs['data_points'] as $dataPointId){
                    $this->dataPointAchievement()->create([
                        'data_point_id' => $dataPointId,
                        'achievement_id' => $achievement->id,
                    ]);
                }
            }

            DB::commit();
            return [
                'success' => true,
                'achievement' => new AdminAchievementResource($achievement)
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

    public function updateAchievement($request, $itemId): array
    {
        $achievement = $this->achievement()->where('item_id', $itemId)->first();

        DB::beginTransaction();
        try {
            $inputs = $request->all();

            if($request->hasFile('icon')){
                $inputs['icon'] = CrudService::uploadAndCompressImage($request, $this->imagePath, null, null, 'icon');
            }

            $inputs['admin_id'] = Auth::id();
            $inputs['slug'] = Str::slug($inputs['name']).BaseService::randomCharacters(10, '0123456789ABCDEFGH');
            $achievement->update($inputs);

            if(!empty($inputs['categories'])){

                $categories = $this->achievementCategory()->where('achievement_id', $achievement->id)->get();
                // remove deleted categories
                foreach ($categories as $category){
                    if(!in_array($category->category_id, $inputs['categories'], true)){
                        $category->delete();
                    }
                }

                $existingCategories = $this->achievementCategory()
                    ->select('category_id', 'achievement_id')
                    ->where('achievement_id', $achievement->id)
                    ->get()->pluck('category_id')->toArray();

                foreach ($inputs['categories'] as $categoryId){
                    if(!in_array($categoryId, $existingCategories, true)){
                        $this->achievementCategory()->create([
                            'category_id' => $categoryId,
                            'achievement_id' => $achievement->id,
                        ]);
                    }
                }

            }

            if(!empty($inputs['data_points'])){
                $dataPoints = $this->dataPoint()->where('achievement_id', $achievement->id)->get();
                // remove deleted categories
                foreach ($dataPoints as $data){
                    if(!in_array($data->data_point_id, $inputs['data_points'], true)){
                        $data->delete();
                    }
                }

                $existingDataPoints = $this->dataPointAchievement()
                    ->select('data_point_id', 'achievement_id')
                    ->where('achievement_id', $achievement->id)
                    ->get()->pluck('data_point_id')->toArray();

                foreach ($inputs['data_points'] as $dataPointId){
                    if(!in_array($dataPointId, $existingDataPoints, true)){
                        $this->dataPointAchievement()->create([
                            'data_point_id' => $dataPointId,
                            'achievement_id' => $achievement->id,
                        ]);
                    }
                }

            }

            DB::commit();
            return [
                'success' => true,
                'achievement' => new AdminAchievementResource($achievement)
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

    public function deleteAchievement($request, $itemId): array
    {
        // Start a transaction
        DB::beginTransaction();
        try {
            $achievement = $this->achievement()->where('item_id', $itemId)->first();
            // Check if the achievement exists
            if (!$achievement) {
                return [
                    'success' => false,
                    'error_message' => 'Achievement not found'
                ];
            }

            // Delete the icon
            if (!empty($achievement->icon)) {
                CrudService::deleteFile($achievement->icon, $this->imagePath);
            }

            // Detach the categories
            if ($achievement->categories && $achievement->categories->count() > 0) {
                $achievement->categories()->detach();
            }

            // Delete the achievement
            $achievement->delete();

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



    public const ACHIEVEMENTS = [
        'icebreaker' => [
            'title' => 'Ice Breaker',
            'description' => "Hooray, you’ve earned your very first badge!",
            'icon' => 'icebreaker.png',
            'user_data' => [
                'name',
                'genre_focus',
                'writing_medium',
            ]
        ],
        'recommendation_ready' => [
            'title' => 'Recommendation Ready',
            'description' => 'You’re ready to get recommendations!',
            'icon' => 'recommendation.png',
            'user_data' => [
                'media',
                'characters',
                'audience',
                'themes',
            ]
        ],
        'process' => [
            'title' => 'Process',
            'description' => 'You’ve got your process down!',
            'icon' => 'process.png',
            'user_data' => [
                'productivity',
                'writing_process',
                'stage_preference',
                'revision_style',
            ]
        ],
        'collaborator' => [
            'title' => 'Collaborator',
            'description' => 'You’re ready to collaborate!',
            'icon' => 'collaborator.png',
            'user_data' => [
                'collaboration_style',
                'adaptability',
            ]
        ],
        'sculptor' => [
            'title' => 'Sculptor',
            'description' => 'You’re ready to sculpt your story!',
            'icon' => 'profession.png',
            'user_data' => [
                'world_engagement',
                'narrative_focus',
            ]
        ],
        'growth_guru' => [
            'title' => 'Growth Guru',
            'description' => 'You’re ready to grow your story!',
            'icon' => 'growth.png',
            'user_data' => [
                'motivation',
                'education',
                'stage',
            ]
        ],
    ];

    private static function collectUserData(User $user): array
    {
        $userData = $user->attributesToArray();
        $userData = array_merge($userData, $user->extra_attributes['onboarding'] ?? [], $user->extra_attributes['data'] ?? []);
        $userData['media'] = $user->favoriteMovies;
        unset($userData['onboarding'], $userData['data']);
        return $userData;
    }

    private static function checkProgress(User $user, array $extracted): array
    {
        $progress = [];
        foreach (Achievement::all() as $achievement) {
            $requirements = $achievement->dataPoints->pluck('slug')->toArray();
            $totalRequirements = count($requirements);
            $metRequirements = $totalRequirements - count(array_diff($requirements, array_keys($extracted)));
            $progress[$achievement->id] = $metRequirements / $totalRequirements * 100;
        }
        return $progress;
    }

    private static function getAchievementProgress(Achievement $achievement, array $extracted): int
    {
        $requirements = $achievement->dataPoints->pluck('slug')->toArray();
        $totalRequirements = count($requirements);
        $metRequirements = $totalRequirements - count(array_diff($requirements, array_keys($extracted)));
        return $metRequirements / $totalRequirements * 100;
    }

    /**
     * @param User $user The user to update achievements for
     * @param array $extracted The extracted data from the user
     * @param Collection $achievements The achievements to check for progress
     * @param Model $target The target model for the achievements
     * @return void
     */
    public static function updateProgress(User $user, array $extracted, Collection $achievements, Model $target): void
    {
        foreach ($achievements as $achievement) {
            $ua = $user->userAchievements()
                ->where('achievement_id', $achievement->id)
                ->where('target_id', $target->id)
                ->where('target_type', get_class($target))
                ->first();

            if ($ua) {
                if ($ua->completed_at) {
                    continue;
                }
            }

            $progress = static::getAchievementProgress($achievement, $extracted);
            if ($progress > 0) {
                if ($ua) {
                    $ua->progress = $progress;
                    $ua->save();
                } else {
                    UserAchievement::create([
                        'achievement_id' => $achievement->id,
                        'user_id' => $user->id,
                        'target_id' => $target->id,
                        'target_type' => get_class($target),
                        'progress' => $progress,
                    ]);
                }
            }
        }
    }
}
