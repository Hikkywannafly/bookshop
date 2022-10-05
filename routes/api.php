<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

// test api : http://127.0.0.1:8000/api/v1/auth/testapi
Route::get('/user', [AuthController::class, 'user']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/refresh', [AuthController::class, 'refreshToken']);

    Route::post('/update', [AuthController::class, 'updateUserProfile']);

    Route::get('/testapi', [AuthController::class, 'testapi']);
});


Route::get('/test', function () {
    return response()->json([
        'message' => 'Hikkywananfly'
    ]);
});
