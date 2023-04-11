<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    const AUTH_TOKEN_LIFETIME_MINUTES = 1;

    public function register(RegisterRequest $request)
    {
        User::create($request->validated());

        return response()->json([
            'date' => Carbon::now()->format('d.m.y H:i:s'),
            'status' => true,
            'message' => 'User created successfully',
        ]);
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'date' => Carbon::now()->format('d.m.y H:i:s'),
                    'status' => false,
                    'message' => 'Email & password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'date' => Carbon::now()->format('d.m.y H:i:s'),
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $user->createToken("API TOKEN", [], now()->addMinutes(self::AUTH_TOKEN_LIFETIME_MINUTES))->plainTextToken,
            ], 200);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
