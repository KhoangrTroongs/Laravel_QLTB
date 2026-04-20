<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle the login request.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = User::withTrashed()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không chính xác.'], 401);
        }

        if ($user->trashed() || $user->available == 0) {
            return response()->json(['message' => 'Tài khoản đã bị vô hiệu hóa.'], 403);
        }

        $user->loadMissing('roles');

        // Xóa token cũ trước khi tạo mới (tránh tích lũy)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user->load('roles')),
        ]);
    }

    /**
     * Handle the register request.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $existingUser = User::withTrashed()->where('email', $validated['email'])->first();
        if ($existingUser) {
            return response()->json([
                'message' => 'Email này đã được sử dụng.',
                'errors' => ['email' => ['Email đã tồn tại hoặc thuộc tài khoản đã xóa.']],
            ], 422);
        }

        $lastUser = User::orderByDesc('id')->first();
        $nextId = $lastUser ? ($lastUser->id + 1) : 1;
        $employeeId = 'USR'.str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'employee_id' => $employeeId,
            'phone' => $validated['phone'] ?? null,
            'status' => 1,
        ]);

        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole->id);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user->load('roles')),
        ], 201);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Đăng xuất thành công.']);
    }

    /**
     * Get the authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json(new UserResource($request->user()->load('roles')));
    }
}
