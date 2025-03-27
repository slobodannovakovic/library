<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        //Gate::authorize('create', User::class);
        
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)]
        ]);

        $fields['role_id'] = 1;

        $user = User::create($fields);

        $user->refresh();

        return response()->json([
            'message' => 'New administrator registered.',
            'user' => new UserResource($user->load('role'))
        ], 201);
    }
}
