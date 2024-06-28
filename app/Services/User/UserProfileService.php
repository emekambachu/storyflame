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

    public function updateUserProfile($request): array
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
        $inputs['photo_path'] = '/'.$this->avatarPath.'/';

        $user->update($inputs);

        return [
            'success' => true,
            'message' => 'Profile updated successfully',
        ];
    }
}
