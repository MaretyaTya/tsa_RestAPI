<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['name'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createtoken('auth_token')->plainTextToken;
        return $this->apiSuccess([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->calidated();

        if (!Auth::attempt($validated)) {
            return $this->apiError('Credential not match', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $validated['email'])->first();
        $token = $user->createToken('Auth_token')->plainTextToken;

        return $this->apiSuccess([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}
