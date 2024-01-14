<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): Response
    {
        $data = $request->validated();

        $user = User::query()->create([
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24);

        return response([
            'user' => new UserResource($user),
        ])->withCookie($cookie);
    }

    public function login(LoginRequest $request): Response
    {
        $data = $request->validated();

        $user = User::query()->where('name', $data['name'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'message' => 'Email or password is incorrect.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24);

        return response([
            'user' => new UserResource($user),
        ])->withCookie($cookie);
    }

    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        $cookie = cookie()->forget('token');

        return response()->withCookie($cookie);
    }

    public function user(Request $request): Response
    {
        return response([
            'user' => new UserResource($request->user()),
        ]);
    }
}
