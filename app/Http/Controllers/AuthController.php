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
use App\Models\UserDetail;
use App\Models\Payment;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\CartSesstion;
use App\Models\CartItem;
use App\Models\Rating;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'login', 'register', 'refreshToken'
        ]]);
    }
    public function register(Request $request)
    {

        $validated = $this->validateData($request);

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
                ]
            ));
            try {

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
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password or email is incorrect'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if (!$user->email_verified_at) {
            return response()->json([
                'status' => 'verify',
                'message' => 'Please verify your email address before login',
                'access_token' => auth()->attempt($validator->validated()),
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
        auth()->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ], 200);
    }
    public function refreshToken()
    {
        $tokenRefresh = auth()->refresh();
        // return $this->createNewAccessToken(auth()->refresh());
        return response()->json([
            'status' => 'success',
            'access_token' => $tokenRefresh,
            'token_type' => 'bearer',
        ]);
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
            'user' => auth()->user(),
        ]);
    }

    public function updateAccount(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Update account successfully',
        ], 200);
    }
    public function getAccount(Request $request)
    {
        $user = auth()->user();
        $user_detail = UserDetail::where('user_id', $user->id)->first();
        $payment = Payment::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Get account successfully',
            'userDetail' => $user_detail,
            'user' => $user,
            'payment' => $payment

        ], 200);
    }

    public function order(Request $request)
    {
        $user = auth()->user();
        $payment = Payment::where('id', $request->payment)->first();
        if ($payment->status === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Phương thức thanh toán hiện không sẵn sàng vui lòng chọn phương thức khác',
            ], 200);
        }
        $user_detail = UserDetail::where('user_id', $user->id)->first();
        // check phone exist
        $exist_phone = User::where('phone', $request->phone)->first();
        if ($user->phone == null && $exist_phone == null) {
            $user->phone = $request->phone;
            $user->save();
        }
        if ($user_detail == null || $user_detail->address == null) {
            $user_detail = UserDetail::create(
                [
                    'user_id' => $user->id,
                    'address' => $request->address,
                    'ward' => $request->ward,
                    'district' => $request->district,
                    'province' => $request->province,
                ]
            );
        }

        $order_detail = OrderDetail::create(
            [
                'user_id' => $user->id,
                'payment_id' => $request->payment,
                'recipient' => $request->name,
                'address' => $request->address,
                'province' => $request->province,
                'district' => $request->district,
                'ward' => $request->ward,
                'phone' => $request->phone,
                'email' => $request->email,
                'note' => $request->note,
            ]
        );
        $order_detail_id = $order_detail->id;
        $items = $request->cartItems;
        foreach ($items as $item) {
            $order = OrderItem::create(
                [
                    'order_detail_id' => $order_detail_id,
                    'book_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                ]
            );
        }
        // delete cart sesstion
        $cart_sesstion = CartSesstion::query()->where('user_id', '=', $user->id)->first();
        foreach ($items as $item) {
            $cart_item = CartItem::where([
                ['book_id', '=', $item['id']],
                ['cart_sesstion_id', '=', $cart_sesstion->id]
            ])->first()->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đặt hàng thành công',
            'cartItems' =>  $cart_item
        ], 200);
    }
    public function readRating(Request $request)
    {
        $user = auth()->user();
        $rating = Rating::query()
            ->with('user')
            ->where('book_id', '=', $request->book_id)
            ->get();
        return response()->json([
            'status' => 'success',
            'rating' =>  $rating
        ], 200);
    }
    public function postRating(Request $request)
    {
        $user = auth()->user();
        $rating = Rating::create(
            [
                'user_id' => $user->id,
                'book_id' => $request->book_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );
        return response()->json([
            'status' => 'success',
            'message' => 'Đánh giá thành công',
            'request' => $request->all()
        ], 200);
    }
}
