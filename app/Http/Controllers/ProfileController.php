<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ChangePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;

class ProfileController extends Controller
{
    /**
     * Trang hồ sơ cá nhân.
     */
    public function show(): View
    {
        $user = Auth::user()->load(['roles', 'equipments' => function ($q) {
            $q->orderBy('equipment_users.ngaymuon', 'desc');
        }]);

        return view('profile.show', compact('user'));
    }

    /**
     * Cập nhật thông tin cá nhân.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && ! str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $request->file('avatar');
            $filename = 'avatar_'.$user->id.'_'.time().'.webp';
            $path = 'avatars/'.$filename;

            $image = Image::decode($file)
                ->cover(300, 300)
                ->encode(new WebpEncoder(quality: 80));

            Storage::disk('public')->put($path, (string) $image);
            $validated['avatar'] = $path;
        }

        // Chỉ cập nhật các trường được phép để tránh lỗ hổng Mass Assignment
        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'avatar' => $validated['avatar'] ?? $user->avatar,
        ]);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Đổi mật khẩu.
     */
    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
