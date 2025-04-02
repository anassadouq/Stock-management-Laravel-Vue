<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'role' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'role' => $fields['role'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        // Creating token with expiration time defined in config
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Verify user
        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Invalid credentials'], 401);
        }

        // Create token
        $token = $user->createToken('myapptoken')->plainTextToken;

        // Set expiration date
        $expiresAt = Carbon::now()->addHours(2); // Token expires in  hours

        // Update token expiration in the database
        $user->tokens->last()->update([
            'expires_at' => $expiresAt
        ]);

        return response([
            'user' => $user,
            'token' => $token,
            'expires_at' => $expiresAt
        ], 200);
    }

    public function logout(Request $request) {
        // Revoke all user's tokens
        $request->user()->tokens->each(function($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    // Check if the token has expired
    public function checkTokenExpiration(Request $request) {
        $token = $request->bearerToken();

        if ($token) {
            $tokenModel = PersonalAccessToken::findToken($token);

            // Check if token exists and if it has expired
            if ($tokenModel && $tokenModel->expires_at && Carbon::parse($tokenModel->expires_at)->isPast()) {
                return response()->json(['message' => 'Token expired'], 401);
            }
        }

        return response()->json(['message' => 'Token valid'], 200);
    }
}