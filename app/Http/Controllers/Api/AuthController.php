<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //validate request
    public function login(Request $request){
        //validate the request...
        $request -> validate([
            'email'=>'required|email',
            'password'=>'required',

        ]);

        //check if the user exists
        $user = User::where('email',$request->email)->first();
        if (!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'user not found',
            ],404);
        }


        //check if password correct
        // $user = User::where('email',$request->email)->first();
        if (!Hash::check($request->password, $user->password)){
            return response()->json([
                'status' => 'error',
                'message' => 'invalid credentials',
            ],401);
        }

        if (!auth()->attempt($request->only('email','password'))){
            return response()->json([
                'message' => 'invalid login details',
            ],401);
        }

        // $user = auth()->user();

        //generate Token
        $token = $user->createToken('auth-token')->plainTextToken;

        //login the user ..
        return response()->json([
          'status' => 'success',
          'token' => $token,
          'user' => $user ,
          ],200);

    }

}
