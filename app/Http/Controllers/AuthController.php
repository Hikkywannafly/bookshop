<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\JWTFactory;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register',]]);
    }


    public function user()
    {
    }
    public function register(Request $request)
    {
        return User::register($request);
    }

    public function login(Request $request)
    {
        return User::login($request);
    }
    public function logout(Request $request)
    {
        return User::logout();
    }
    public function refreshToken()
    {
        return User::refreshToken();
    }
    public function updateUserProfile(Request $request)
    {
    }
    public function forgotPassword(Request $request)
    {
    }
    public function resetPassword(Request $request)
    {
    }


    public function testapi()
    {
        return response()->json(['message' => 'test api']);
    }
}
