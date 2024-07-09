<?php

namespace App\Services\User;

use App\Models\TokenUsage;
use App\Models\User;
use App\Services\Base\BaseService;
use App\Services\Base\CrudService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

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

    public function updateUserEmail($request): array
    {
        $inputs = $request->all();
        $user = $this->user()->find(Auth::id());

        Session::put('new_email', $inputs['email']);

        $token = Str::random(6);

        TokenUsage::create([
            'key' => $token,
            'user_id' => Auth::id(),
            'target_type' => 'App\Models\User',
            'target_id' => Auth::id(),
            'model' => 'App\Models\User',
        ]);

        $emailData = [
            'token' => $token,
            'name' => $user->name ?? $user->first_name.' '.$user->last_name,
        ];

        BaseService::sendEmailGeneral(
            $emailData,
            'emails.users.profile.email-change',
            'Email Change Confirmation',
            $inputs['email'],
            $emailData['name']
        );

        return [
            'success' => true,
            'message' => 'Password token successfully sent to your email!',
        ];
    }

    public function confirmEmailTokenFromUser($request): array
    {
        $inputs = $request->all();
        $user = $this->user()->find(Auth::id());

        $tokenApproved = TokenUsage::where(function ($query) use ($inputs) {
            $query->where('key', $inputs['token'])
                ->where('user_id', Auth::id())
                ->where('target_id', Auth::id())
                ->where('target_type', 'App\Models\User')
                ->where('model', 'App\Models\User');
        })->exists();

        if(!$tokenApproved){
            return [
                'success' => false,
                'errors' => ['token' => ['Token is invalid!']],
            ];
        }

        $user->update([
            'email' => Session::get('new_email'),
        ]);

        Session::forget('new_email');

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

        $user->avatar()->updateOrCreate([
            'imageable_id' => $user->id,
            'imageable_type' => pathinfo($inputs['avatar'], PATHINFO_EXTENSION),
            'name' => $inputs['avatar'],
            'path' => '/'.$this->avatarPath.'/'
        ]);

        return [
            'success' => true,
            'message' => 'Avatar updated successfully',
        ];
    }
}
