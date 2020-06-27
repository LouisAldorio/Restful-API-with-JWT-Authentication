<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use App\Http\Resources\userResources;
use App\Http\Requests\UserUpdatePasswordRequest as UpdatePassword;
use App\Http\Requests\UserProfileUpdateRequest as UpdateProfile;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $user = JWTAuth::user();
        return response()->json([
            'status' => true,
            'message' => 'Here is your Profile!!',
            'User' => $user
        ],200);
    }

    public function updatePassword(UpdatePassword $request)
    {
        $email = $request->email;
        $oldPassword = $request->oldPassword;
        $newPassword = $request->newPassword;
        $authenticatedUserId = JWTAuth::user()->id;
        $user = User::where('id',$authenticatedUserId)->first();
        $userName = $user->name;
        $userEmail = $user->email;

        if(password_verify($oldPassword,$user->password))
        {
            $user->update([
                'name' => $userName,
                'email' => $userEmail,
                'password' => bcrypt($newPassword)
            ]);
            $newUpdatedUser = User::where('id',$authenticatedUserId)->first();
            return response()->json([
                'status' => true,
                'message' => 'Password succesfully changed!',
                'user' => $newUpdatedUser
            ]);
        }
    }


    public function update(UpdateProfile $request)
    {
        $email = $request->email;
        $name = $request->name;
        $authenticatedUserId = JWTAuth::user()->id;
        $user = User::where('id',$authenticatedUserId)->first();

        $user->update([
            'name' => $name,
            'email' => $email
        ]);
        $newUpdateduser = User::where('id',$authenticatedUserId)->first();
        return response()->json([
            'status' => true,
            'message' => 'Profile successfully updated!',
            'user' => $newUpdateduser
        ]);
    }
}
