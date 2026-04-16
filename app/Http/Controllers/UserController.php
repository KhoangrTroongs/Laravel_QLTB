<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('employee_id', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $sortField = $request->get('sort', 'id');
        $sortDir   = $request->get('direction', 'asc');
        $allowed   = ['id', 'name', 'email', 'employee_id', 'status'];
        if (! in_array($sortField, $allowed)) {
            $sortField = 'id';
        }
        $query->orderBy($sortField, $sortDir === 'desc' ? 'desc' : 'asc');

        $users = $query->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Nhân viên đã được thêm thành công!');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Logic bảo vệ Admin
        if ($user->employee_id === 'ADMIN001' && isset($validated['status']) && $validated['status'] == 0) {
            return back()->withErrors(['status' => 'Không thể đặt trạng thái nghỉ việc cho tài khoản Quản trị viên hệ thống!']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Thông tin nhân viên đã được cập nhật!');
    }

    public function destroy(User $user)
    {
        if ($user->employee_id === 'ADMIN001') {
            return back()->with('error', 'Không được phép xóa tài khoản Quản trị viên hệ thống!');
        }

        // Kiểm tra xem có đang mượn thiết bị nào không (status = 1 trong bảng trung gian)
        $isBorrowing = $user->equipments()->wherePivot('status', 1)->exists();

        if ($isBorrowing) {
            return back()->with('error', 'Không thể xóa nhân viên này vì đang có thiết bị mượn chưa hoàn trả!');
        }

        // Thực hiện xoá mềm: set available = 0 và delete()
        $user->update(['available' => 0]);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Nhân viên đã được đưa vào thùng rác thành công!');
    }
}
