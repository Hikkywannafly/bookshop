<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'verify-email/{id}/{hash}', 'email/verification-notification', 'verify'
        ]]);
    }
    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return  response()->json([
                'message' => 'Already Verified',
                'test' =>  $request->user()->hasVerifiedEmail()
            ]);
        }

        $request->user()->sendEmailVerificationNotification();


        return response()->json([
            'status' => 'success',
            'message' => 'Verification link sent on your email id'
        ]);
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return  response()->json([
                'message' => 'Email already verified'
            ]);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            'message' => 'Email has been verified',
            'request' => $request
        ]);
    }
    public function testapi1()
    {
        return response()->json([
            'message' => 'Hello World'
        ]);
    }
}
