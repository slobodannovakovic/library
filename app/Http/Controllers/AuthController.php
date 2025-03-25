<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): array
    {
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create($fields);
        $token = $user->createToken($user->email)->plainTextToken;

        $user->refresh();

        return [
            'message' => 'New user registered.',
            'user' => new UserResource($user->load('role')),
            'token' => $token
        ];
    }

    public function login(Request $request): array
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::with('role')->firstWhere('email', $credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return ['error' => 'Invalid credentials.'];
        }

        $token = $user->createToken($user->email)->plainTextToken;

        return [
            'message' => 'User logged in.',
            'user' => new UserResource($user),
            'token' => $token
        ];
    }

    public function logout(Request $request): array
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'User logged out'
        ];
    }
 }
