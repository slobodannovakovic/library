<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Password as PasswordFacade;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create($fields);
        $token = $user->createToken($user->email)->plainTextToken;

        $user->refresh();

        return response()->json([
            'message' => 'New user registered.',
            'user' => new UserResource($user->load('role')),
            'token' => $token
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::with('role')->firstWhere('email', $credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid login credentials.',
                'errors' => ['login' => 'Invalid login credentials.']
            ]);
        }

        $token = $user->createToken($user->email)->plainTextToken;

        return response()->json([
            'message' => 'User logged in.',
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'User logged out'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|exists:users,email',
            'frontendUrl' => 'required'
        ]);
    
        $user = User::where('email', $fields['email'])->firstOrFail();
    
        $token = PasswordFacade::createToken($user);
     
        $user->notify(new ResetPasswordNotification($token, $fields['frontendUrl']));
    
        return response()->json(['message' => 'Password reset link sent.']);
    }

    public function resetPassword(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed'],
            'token' => ['required']
        ]);

        $status = PasswordFacade::reset(
            $fields,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === PasswordFacade::PASSWORD_RESET) {
            return response()->json(['message' => 'User password reset.']);
        }

        return response()->json([
            'errors' => [
                'resetPassword' => 'Error. User password not reset.'
            ]
        ]);
    }
 }
