<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Illuminate\Support\Facades\Hash;

class Account extends Model implements JWTSubject
{
    use HasFactory;

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
    // handel register
    public static function register(Request $request)
    {
        try {
            $validated = Account::validateData($request);
            if ($validated->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validated->errors()
                ], Response::HTTP_BAD_REQUEST);
            } else {
                $email = $request->input('email');
                $name = $request->input('name');
                $phone = $request->input('phone');
                $password = $request->input('password');
                $checkMail = Account::checkExistUser('email', $email);
                $checkPhone = Account::checkExistUser('phone', $phone);
                if (!$checkMail || !$checkPhone) {
                    return response()->json([
                        'status' => 'error',
                        'email' => !$checkMail ?  'Email already exists' : null,
                        'phone' => !$checkPhone ?  'Phone already exists' : null,
                    ], Response::HTTP_BAD_REQUEST);
                }
                if ($checkMail && $checkPhone) {
                    $account = Account::create([
                        'email' => $email,
                        'name' => $name,
                        'phone' => $phone,
                        'password' => Hash::make($password),
                        'role' => 'user',
                        'status' => false,
                    ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Register successfully',
                        'data' => $account
                    ], Response::HTTP_OK);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error all',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
    // handle login
    public static function login(request $request)
    {
        $input = $request->only('email', 'password');
        $validated = Validator::make($input, [
            'email' => 'required|email|min:5|max:255',
            'password' => 'required'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validated->errors()
            ], 422);
        }
        $account =  Account::where('email', $request->email)->first();
        if (!$account || Hash::check($request->password, $account->password) == false) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password or email is incorrect',
            ], Response::HTTP_BAD_REQUEST);
        }
        $token = FacadesJWTAuth::fromUser($account);
        // $account->update([
        //     'refresh_token' => $token
        // ]);
        return Account::createNewToken($token, $account);
    }


    public static function validateData(Request $request)
    {
        return Validator::make($request->all(), [
            'phone' => 'required|min:10|max:11',
            'email' => 'required|email|min:5|max:255',
            'name' => 'required|min:3|max:255',
            'password' => 'required|min:6|max:255',
        ]);
    }

    public static function checkExistUser($type, $value)
    {
        $__result =  DB::table('accounts')->where($type, $value)->first();
        if ($__result != null) {
            return false;
        }
        return true;
    }
    // create new access token
    protected function createNewAccessToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>  FacadesJWTAuth::factory()->getTTL() * 60,
        ]);
    }

    protected function createNewToken($token, $account)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>  FacadesJWTAuth::factory()->getTTL() * 60,
            'user' => $account,
        ],  Response::HTTP_OK);
    }
}
