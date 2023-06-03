<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if(!auth()->attempt($loginData)) {
            return response(['message' => 'email atau password salah'], 400);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['users'=>auth()->user(), 'access_token'=> $accessToken]);

    }

    // public function logout()
    // {
    //     if (Auth()->check()) {
    //     Auth::user()->AauthAcessToken()->delete();
    //     return response(['message' => 'berhasil logout']);
    //     }
    // }
}