<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo các vai trò mặc định
        $admin = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Admin', 'description' => 'Toàn quyền hệ thống']
        );

        $editor = Role::firstOrCreate(
            ['name' => 'editor'],
            ['display_name' => 'Editor', 'description' => 'Thêm, sửa, xem dữ liệu']
        );

        Role::firstOrCreate(
            ['name' => 'user'],
            ['display_name' => 'User', 'description' => 'Người dùng thông thường']
        );

        // Gán vai trò cho tất cả người dùng
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        User::all()->each(function ($user) use ($adminRole, $userRole) {
            if ($user->employee_id === 'ADMIN001') {
                $user->roles()->sync([$adminRole->id]);
            } else {
                // Gán vai trò 'user' cho tất cả những người còn lại
                $user->roles()->sync([$userRole->id]);
            }
        });
    }
}
