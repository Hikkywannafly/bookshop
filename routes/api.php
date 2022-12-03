<?php

use App\Http\Controllers\Admin\AuthAdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthSocialController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartSesstionController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Models\Book;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// test api : http://127.0.0.1:8000/api/auth/testapi
Route::get('/user', [AuthController::class, 'user']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/login-with-google', [AuthController::class, 'loginWithGoogle']);

    Route::post('/register-with-google', [AuthController::class, 'registerWithGoogle']);

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/refresh', [AuthController::class, 'refreshToken']);

    Route::post('/update', [AuthController::class, 'updateUserProfile']);

    Route::get('/testapi', [AuthController::class, 'testapi']);

    Route::post('/email/verification-notification', [VerificationController::class, 'sendVerificationEmail']);

    Route::get('/verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

    Route::get('/email/testapi1', [VerificationController::class, 'testapi1']);

    Route::get('/cart', [CartSesstionController::class, 'index']);

    Route::post('/cart', [CartSesstionController::class, 'create']);

    Route::patch('/cart', [CartSesstionController::class, 'update']);

    Route::delete('/cart1', [CartSesstionController::class, 'destroy']);
});

// admin
Route::group([

    'middleware' => ['api', 'isApiAdmin'],
    'prefix' => 'auth-admin'
], function ($router) {

    Route::get('/admin1', [AuthAdminController::class, 'index']);

    Route::get('/user', [AuthAdminController::class, 'index']);

    Route::get('/read-product', [AuthAdminController::class, 'readProduct']);

    Route::post('/create-product', [AuthAdminController::class, 'create']);

    Route::get('/edit-product/{slug}', [AuthAdminController::class, 'edit']);

    Route::post('/update-product', [AuthAdminController::class, 'update']);

    Route::post('/delete-product', [AuthAdminController::class, 'delete']);
});

Route::post('/auth/google', [AuthSocialController::class, 'handleLoginGoogle']);

Route::get('/category/{slug}', [CategoryController::class, 'index']);

Route::get('/category/{slug}/{sub_slug}', [SubCategoryController::class, 'index']);

Route::get('/suppliers/{slug}', [CategoryController::class, 'getSuppliers']);

Route::get('/suppliers/{slug}/{sub_slug}', [SubCategoryController::class, 'getSubSuppliers']);

Route::get('/product/{slug}', [BookController::class, 'index']);

Route::post('/upload', [BookController::class, 'create']);

Route::post('/search', [BookController::class, 'search']);



Route::get('/test', function () {
    return response()->json([
        'message' => 'Hikkywananfly'
    ]);
});
