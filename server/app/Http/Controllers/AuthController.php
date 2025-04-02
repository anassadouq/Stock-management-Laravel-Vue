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
            'role' => 'required|string|in:super_admin,admin',
            'email' => 'required|string|email|unique:users,email',
        ]);

        // Set password as the name
        $user = User::create([
            'name' => $fields['name'],
            'role' => $fields['role'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['name']) // Secure password hashing
        ]);

        // Create API token
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // Verify user
        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Invalid credentials'], 401);
        }

        // Create token and set expiration
        $token = $user->createToken('myapptoken')->plainTextToken;
        $expiresAt = Carbon::now()->addHours(2); // Token expires in 2 hours

        return response([
            'user' => $user,
            'token' => $token,
            'expires_at' => $expiresAt
        ], 200);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete(); // Revoke all tokens

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function checkTokenExpiration(Request $request) {
        $token = $request->bearerToken();

        if ($token) {
            $tokenModel = PersonalAccessToken::findToken($token);

            if ($tokenModel && $tokenModel->expires_at && Carbon::parse($tokenModel->expires_at)->isPast()) {
                return response()->json(['message' => 'Token expired'], 401);
            }
        }

        return response()->json(['message' => 'Token valid'], 200);
    }
}