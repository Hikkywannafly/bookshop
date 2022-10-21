<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Carbon\Carbon;

class AuthSocialController extends Controller
{
    public function handleLoginGoogle(Request $request)
    {
        try {

            $data = $request->all();
            $email = User::where('email', $data['email'])->first();
            $name = $data['name'];
            $avatar = $data['picture'];
            $email_verified = $data['email_verified'];

            if ($email != null) {
                if (!$token = auth()->attempt(['email' => $data['email'], 'password' => env('GOOGLE_PASSWORD')])) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unauthorized',
                    ], 401);
                }
            }
            if (!$email_verified) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }
            if ($email == null) {
                $user = User::create([
                    'name' => $name,
                    'email' => $data['email'],
                    'password' => bcrypt(env('GOOGLE_PASSWORD')),
                    'phone' => null,
                    'image_address' => $avatar,
                    'email_verified_at' => Carbon::now(),
                ]);

                if (!$token = auth()->attempt(['email' => $data['email'], 'password' => env('GOOGLE_PASSWORD')])) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unauthorized',
                    ], 401);
                }
            }
            return $this->createNewAccessToken($token);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    protected function createNewAccessToken($token)
    {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => FacadesJWTAuth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
