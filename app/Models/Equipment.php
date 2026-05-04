<?php

namespace App\Models;

use Database\Factories\EquipmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model đại diện cho một thiết bị trong hệ thống.
 *
 * @property string $name Tên thiết bị
 * @property string $model Mã model/số hiệu
 * @property string $image Đường dẫn ảnh (WebP)
 * @property string $description Mô tả chi tiết
 * @property int $status Trạng thái hoạt động (1: Hoạt động, 0: Ngưng)
 * @property int $available Trạng thái sẵn sàng (1: Có thể mượn, 0: Đã xóa/ẩn)
 * @property int $category_id ID danh mục
 * @property array $spec Thông số kỹ thuật động (JSON)
 */
class Equipment extends Model
{
    /** @use HasFactory<EquipmentFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'model',
        'image',
        'description',
        'status',
        'available',
        'category_id',
        'spec',
    ];

    protected function casts(): array
    {
        return [
            'spec' => 'array',
        ];
    }

    /**
     * Quan hệ: Một thiết bị thuộc về một danh mục.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Quan hệ: Một thiết bị có thể được mượn bởi nhiều người dùng (qua lịch sử).
     * Sử dụng bảng trung gian equipment_users.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'equipment_users')
            ->withPivot('id', 'ngaymuon', 'hantra', 'ngaytra', 'status', 'description')
            ->withTimestamps();
    }
}
