<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\Base\CrudService;
use Illuminate\Support\Facades\Auth;

class UserProfileService
{
    protected $photoPath = 'uploads/photos';

    public function user(): User
    {
        return new User();
    }

    public function updateUserProfile($request): array
    {
        $inputs = $request->all();
        $user = $this->user()->find(Auth::id());

        $inputs['photo'] = CrudService::uploadAndCompressImage($request, $this->photoPath, null, null, 'photo');
        $inputs['photo_path'] = '/'.$this->photoPath.'/';

        $user->update($inputs);

        return [
            'success' => true,
            'message' => 'Profile updated successfully',
        ];
    }
}
