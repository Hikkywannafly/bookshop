<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Events\Registered;

use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use App\Mail\UserVerification;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'login', 'register', 'email/verification-notification', 'testapi'
        ]]);
    }
    public function register(Request $request)
    {

        $validated =  $this->validateData($request);

        if ($validated->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Validation Error',
                    $validated->errors()
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $checkMail =  $this->checkExistUser('email', $request->email);
        $checkPhone =  $this->checkExistUser('phone', $request->phone);

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
                [
                    'password' => bcrypt($request->password),
                    'confirm' => false,
                    'confirmation_code' => rand(100000, 999999),
                    'confirmation_code_expired_in' => Carbon::now()->addSecond(60)
                ]
            ));
            try {
                // Mail::to($user->email)->send(new UserVerification($user));
                event(new Registered($user));

                return response()->json([
                    'status' => 'success',
                    'message' => 'Registered,verify your email address to login',
                    'access_token' => auth()->attempt($validated->validated()),
                    'user' => $user
                ], 201);
            } catch (\Exception $err) {
                $user->delete();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not send email verification,please try again',
                    'error' => $err->getMessage()
                ], 500);
            }

            // event(new Registered($user))
        }
    }

    public function login(Request $request)
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
        $user = User::where('email', $request->email)->first();
        if (!$user->emailverify) {
            return response()->json([
                'status' => 'verify',
                'message' => 'Please verify your email address before login'
            ], Response::HTTP_UNAUTHORIZED);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password or email is incorrect',
            ], 401);
        }
        return $this->createNewAccessToken($token);
    }


    public function logout()
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

    // chore
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
            'email' => 'required|string|email|max:100|',
            'password' => 'required|string|min:6',
        ]);
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

            // $mail->SMTPDebug = 0;
            // $mail->isSMTP();
            // $mail->Host = 'smtp.gmail.com';
            // $mail->SMTPAuth = true;
            // $mail->Username = 'ggraygon@gmail.com';
            // $mail->Password = 'wbhfkresydraogfr';
            // $mail->SMTPSecure = 'tls';
            // $mail->Port = 587;
            // $mail->setFrom('ggraygon@gmail.com', 'HouseHub');
            // $mail->addAddress('hieudao2906@gmail.com');
            // $mail->isHTML(true);
            // $mail->Subject = 'Reset password';
            // $mail->Body    = 'Mật khẩu mới của bạn là: ';
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            // if (!$mail->send()) {
            //     return response(['message' => 'Error'], Response::HTTP_BAD_REQUEST);
            // }