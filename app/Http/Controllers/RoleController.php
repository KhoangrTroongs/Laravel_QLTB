<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\AssignRoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Danh sách vai trò và phân công.
     */
    public function index(Request $request): View
    {
        $roles = Role::withCount('users')->get();
        
        $query = User::query()->with('roles');

        // Filter by role
        if ($request->filled('role_id')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        // Search in roles page
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('employee_id', 'like', '%' . $request->search . '%');
            });
        }

        // Sort logic
        $sortField = $request->get('sort', 'id');
        $sortDir   = $request->get('direction', 'desc');
        $allowed   = ['id', 'name', 'employee_id', 'role'];
        
        if (! in_array($sortField, $allowed)) {
            $sortField = 'id';
        }

        if ($sortField === 'role') {
            $query->orderBy(
                Role::select('name')
                    ->join('role_user', 'roles.id', '=', 'role_user.role_id')
                    ->whereColumn('role_user.user_id', 'users.id')
                    ->limit(1),
                $sortDir === 'asc' ? 'asc' : 'desc'
            );
        } else {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $users = $query->paginate(20)->withQueryString();

        return view('roles.index', compact('roles', 'users'));
    }

    /**
     * Gán vai trò cho người dùng.
     */
    public function assignRole(AssignRoleRequest $request, User $user): RedirectResponse
    {
        // Bảo vệ tuyệt đối tài khoản ADMIN001
        if ($user->employee_id === 'ADMIN001') {
            return back()->with('error', 'Không được phép thay đổi vai trò của tài khoản Quản trị viên hệ thống!');
        }

        $validated = $request->validated();

        // Sync single role (many-to-many relationship but used as one-to-one here)
        $user->roles()->sync([$validated['role_id']]);

        return back()->with('success', 'Cập nhật vai trò cho "'.$user->name.'" thành công!');
    }

    /**
     * Gỡ một vai trò khỏi người dùng.
     */
    public function removeRole(User $user, Role $role): RedirectResponse
    {
        // Bảo vệ tuyệt đối tài khoản ADMIN001
        if ($user->employee_id === 'ADMIN001') {
            return back()->with('error', 'Không được phép gỡ vai trò của tài khoản Quản trị viên hệ thống!');
        }

        $user->roles()->detach($role->id);

        return back()->with('success', 'Đã gỡ vai trò "'.$role->display_name.'" khỏi '.$user->name.'!');
    }
}
