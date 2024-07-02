<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\Base\CrudService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $user = $this->user()->find(Auth::id());

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

        if(!empty($user->password) && !Hash::check($inputs['current_password'], $user->password)){
            return [
                'success' => false,
                'errors' => ['current_password' => ['Current password is incorrect!']],
            ];
        }

        $inputs['password'] = Hash::make($inputs['new_password']);
        $user->update($inputs);

        return [
            'success' => true,
            'message' => 'password updated successfully',
        ];
    }

    public function updateUserAvatar($request): array
    {
        $inputs = $request->all();
        $user = $this->user()->with('avatar')->find(Auth::id());

        $inputs['avatar'] = CrudService::uploadAndCompressImage(
            $request,
            $this->avatarPath,
            null,
            null,
            'avatar'
        );
        $inputs['avatar_path'] = '/'.$this->avatarPath.'/';

        $user->avatar->updateOrCreate([
            'imageable_id' => $user->id,
            'name' => $inputs['avatar'],
            'path' => $inputs['avatar_path']
        ]);

        return [
            'success' => true,
            'message' => 'Avatar updated successfully',
        ];
    }
}
