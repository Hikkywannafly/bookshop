<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'image_address',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'refresh_token',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public static function register(Request $request)
    {

        $validated = User::validateData($request);

        if ($validated->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Validation Error',
                    'errors' => $validated->errors()
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $checkMail = User::checkExistUser('email', $request->email);
        $checkPhone = User::checkExistUser('phone', $request->phone);

        if ($checkMail || $checkPhone) {
            return response()->json([
                'status' => 'error',
                'email' => $checkMail ?  'Email already exists' : null,
                'phone' => $checkPhone ?  'Phone already exists' : null,
            ], Response::HTTP_BAD_REQUEST);
        }
        if (!$checkMail && !$checkPhone) {
            $user = User::create(array_merge(
                $validated->validated(),
                ['password' => bcrypt($request->password)]
            ));
            return response()->json([
                'status' => 'success',
                'message' => 'User successfully registered',
                'user' => $user
            ], 201);
        }
    }
    public static function checkExistUser($type, $value)
    {
        $__result =  DB::table('users')->where($type, $value)->first();
        if ($__result != null) {
            return true;
        }
        return false;
    }
    public static function validateData(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'phone' => 'required|min:10|max:11',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
    }

    // handle login
    public static function login(request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|min:5|max:255',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password or email is incorrect',
            ], 401);
        }
        return User::createNewAccessToken($token);
    }

    public static function refreshToken()
    {
        return User::createNewAccessToken(auth()->refresh());
    }

    public static function logout()
    {
        FacadesJWTAuth::logout();
        return response()->json(['message' => 'User successfully signed out']);
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
