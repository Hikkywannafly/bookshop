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
        $this->middleware('auth:api', ['except' => ['login', 'register', 'testapi']]);
    }


    public function user()
    {
    }
    public function register(Request $request)
    {
        return Account::register($request);
    }

    public function login(Request $request)
    {
        return Account::login($request);
    }
    public function logout(Request $request)
    {
    }
    public function refreshToken(Request $request)
    {
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
        return User::create([
            'name' => 'Hikkywananfly',
            'email' => 'test@gmail.com',
            'password' => 'test@gmail.com',
        ]);
    }
}
