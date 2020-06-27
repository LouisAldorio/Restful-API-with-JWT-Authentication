<?php

namespace App\Http\Controllers\API\v1;
use App\Http\Controllers\Controller;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRecoverRequest as RecoverRequest;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function login(Request $request)
    {
        $credentials = $request->only('email','password');
        $token = null;

        if(!$token = JWTAuth::attempt($credentials)){
            return response()->json([
                "status" => false,
                "message" => "Unauthorized"
            ]);
        }

        $user = User::where('email' ,$request->email)->first();
        return response()->json([
            "status" => true,
            "token" => $token,
            "user" => $user
        ]);
    }

    public function register(Request $request)
    {
        $this->validate($request,[
            "name" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "required|string|min:6|max:10|confirmed"
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if($this->loginAfterSignUp)
        {
            return $this->login($request);
        }

        return response()->json([
            "status" => true,
            "user" => $user
        ]);
    }


    public function logout(Request $request)
    {
        $this->validate($request,[
            "token" => "required"
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                "status" => true,
                "message" => "User Logged out Succesfully"
            ]);
        }
        catch(JWTException $exception)
        {
            return response()->json([
                "status" => false,
                "message" => "You need a proper token to log out!"
            ]);
        }
    }


    public function recover(RecoverRequest $request)
    {
        $email = $request->email;

        $user = User::where('email',$email)->first();

        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'Email not found!'
            ]);
        }
        else if($user){
            $newPassword = bcrypt('new');

            $user->update([
                'password' => $newPassword
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Found!,login with temporary password to reset your new password immediately!',
                'name' => $user->name,
                'Temporary password' => 'new'
            ]);
        }

    }
    
}
