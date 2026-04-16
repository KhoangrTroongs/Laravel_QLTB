<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        // Cho phép tìm cả người dùng đã bị xoá mềm (để middleware CheckBanned xử lý redirect)
        $user = User::withTrashed()->where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            $user->loadMissing('roles');

            // Nếu tài khoản bị xoá mềm hoặc disable, middleware CheckBanned sẽ đẩy về /banned
            // Nhưng nếu là admin/editor thì vẫn check role như cũ
            if (! $user->hasAnyRole(['admin', 'editor'])) {
                return redirect()->route('home');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    /**
     * Show the register form.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle the register request.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Kiểm tra xem email đã tồn tại (bao gồm cả nhân viên đã bị xoá mềm)
        $existingUser = User::withTrashed()->where('email', $validated['email'])->first();
        if ($existingUser) {
            return back()->withErrors([
                'email' => 'Email này đã được sử dụng hoặc thuộc về một nhân viên cũ. Vui lòng liên hệ Admin để khôi phục tài khoản.'
            ])->withInput();
        }

        // Tự động tạo employee_id
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

        // Gán vai trò 'user' mặc định
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole->id);
        }

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký thành công! Chào mừng bạn, '.$user->name.'!');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }
}
