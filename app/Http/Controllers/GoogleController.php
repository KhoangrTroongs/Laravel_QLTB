<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Chuyển hướng người dùng đến trang đăng nhập của Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý dữ liệu trả về từ Google.
     */
    public function handleGoogleCallback()
    {
        try {
            /** @var \Laravel\Socialite\Two\User $user */
            $user = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Không thể kết nối với Google. Vui lòng thử lại.');
        }

        // Tìm user theo google_id
        $finduser = User::where('google_id', $user->id)->first();

        if ($finduser) {
            // Kiểm tra xem user có bị khóa không
            if ($finduser->status == 0 || $finduser->available == 0) {
                return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa hoặc ngừng hoạt động.');
            }

            Auth::login($finduser);

            return redirect()->route('home');
        } else {
            // Nếu chưa có google_id, tìm theo email
            $existingUser = User::where('email', $user->email)->first();

            if ($existingUser) {
                // Cập nhật google_id cho user đã tồn tại
                $existingUser->update([
                    'google_id' => $user->id,
                ]);
                Auth::login($existingUser);

                return redirect()->route('home');
            } else {
                // Tạo user mới nếu email chưa tồn tại trong hệ thống
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => Hash::make(Str::random(16)), // Mật khẩu ngẫu nhiên
                    'employee_id' => 'G-'.strtoupper(Str::random(6)), // Tạo mã nhân viên tạm thời
                    'status' => 1,
                    'available' => 1,
                    'avatar' => $user->avatar,
                ]);

                Auth::login($newUser);

                return redirect()->route('home');
            }
        }
    }
}
