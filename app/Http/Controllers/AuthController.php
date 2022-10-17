<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields=$request->validate([
            'name'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed'
        ]);
        $user=User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password'])
        ]);
        $token=$user->createToken('myapptoken')->plainTextToken;
        $response=[
            'user'=>$user,
            'token'=>$token
        ];
        return response($response,201);
    }

    public function logout(Request $request){
        //get bearer token from the request
        $accessToken=$request->bearerToken();
        //get access token from database
        $token=PersonalAccessToken::findToken($accessToken);
        //revoke token
        $token->delete();
        return [
            'message'=>'Logged out'
        ];
    }
    //login user and send bearer token
    public function login(Request $request){
        $fildes=$request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);
        //check email
        $user=User::where('email',$fildes['email'])->first();
        //check password
        if(!$user || !Hash::check($fildes['password'],$user->password)){
            return response([
                'message'=>'Bad Cradintials'
            ],401);
        }
        $token=$user->createToken('myapptoken')->plainTextToken;

        $response=[
            'user'=> $user,
            'token'=> $token
        ];
        return response($response,201);
    }
    public function getUser(){
        return User::all();
    }
}
