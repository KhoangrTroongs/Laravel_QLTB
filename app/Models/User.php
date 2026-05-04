<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model đại diện cho người dùng/nhân viên trong hệ thống.
 * Tích hợp hệ thống phân quyền (RBAC) và Soft Deletes.
 *
 * @property string $name Họ tên
 * @property string $email Email đăng nhập
 * @property string $employee_id Mã nhân viên
 * @property string $phone Số điện thoại
 * @property int $status Trạng thái tài khoản (1: Hoạt động, 0: Khóa)
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'available',
        'employee_id',
        'phone',
        'address',
        'avatar',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Quan hệ: Một người dùng có thể mượn nhiều thiết bị.
     */
    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'equipment_users')
            ->withPivot('id', 'ngaymuon', 'hantra', 'ngaytra', 'status', 'description')
            ->withTimestamps();
    }

    /**
     * Quan hệ: Một người dùng có thể có nhiều vai trò (admin, editor, etc.).
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if the user has any of the given roles.
     *
     * @param  string[]  $roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user is an editor.
     */
    public function isEditor(): bool
    {
        return $this->hasRole('editor');
    }
}
