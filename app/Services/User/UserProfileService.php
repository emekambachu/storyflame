<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\Base\CrudService;
use Illuminate\Support\Facades\Auth;

class UserProfileService
{
    protected string $avatarPath = 'uploads/avatars';

    public function user(): User
    {
        return new User();
    }

    public function updateUserBio($request): array
    {
        $inputs = $request->all();
        $user = $this->user()->find(Auth::guard('api')->id());
        $user->update($inputs);

        return [
            'success' => true,
            'message' => 'Bio updated successfully',
        ];
    }

    public function updateUserPassword($request): array
    {
        $inputs = $request->all();
        $user = $this->user()->find(Auth::id());

        $inputs['avatar'] = CrudService::uploadAndCompressImage(
            $request,
            $this->avatarPath,
            null,
            null,
            'avatar'
        );
        $inputs['avatar_path'] = '/'.$this->avatarPath.'/';

        $user->update($inputs);

        return [
            'success' => true,
            'message' => 'Profile updated successfully',
        ];
    }

    public function updateUserAvatar($request): array
    {
        $inputs = $request->all();
        $user = $this->user()->find(Auth::id());

        $inputs['avatar'] = CrudService::uploadAndCompressImage(
            $request,
            $this->avatarPath,
            null,
            null,
            'avatar'
        );
        $inputs['avatar_path'] = '/'.$this->avatarPath.'/';

        $user->update($inputs);

        return [
            'success' => true,
            'message' => 'Profile updated successfully',
        ];
    }
}
