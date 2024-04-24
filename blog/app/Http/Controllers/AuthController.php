<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //register
    public function register(Request $request){

        $attrs = $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'=>$attrs['name'],
            'email'=>$attrs['email'],
            'password'=>bcrypt($attrs['password'])
        ]);


        return response([
            'user'=>$user,
            'token'=>$user->createToken('token-name', ['server:update'])->plainTextToken,
        ]);
    }

    // login
    public function login(Request $request){

        $attrs = $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6',
        ]);

        if(!Auth::attempt($attrs)){
            return response([
                'message'=> 'Invalid credentials.'
            ],403);

        }

        return response([
            'user'=>$request->user(),
            'token'=>$request->user()->createToken('token-name', ['server:update'])->plainTextToken,
        ],200);
    }

     // logout
     public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message'=> 'logout success.'
        ],200);
     }

     // logout
     public function user()
     {
        return response([
            'user'=> auth()->user()
        ],200);
     }


     public function update(Request $request)
     {
        $attrs = $request->validate([
            'name' -> 'required|string'
        ]);

        $image = $this->saveImage($request->$image,'profiles');

        auth()->user()->update([
            'name'=>$attrs['name'],
            'image'=>$image
        ]);
        return response([
            'message'=>'User Updated.',
            'user'=> auth()->user()
        ],200);
     }

}
