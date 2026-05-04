<?php

namespace App\Models;

use Database\Factories\EquipmentUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model bảng trung gian cho việc mượn trả thiết bị.
 * Lưu trữ thông tin chi tiết về mỗi lượt mượn: ngày mượn, hạn trả, trạng thái.
 */
class EquipmentUser extends Model
{
    /** @use HasFactory<EquipmentUserFactory> */
    use HasFactory;

    const STATUS_PENDING = 0;

    const STATUS_BORROWING = 1;

    const STATUS_REJECTED = 2;

    const STATUS_RETURNED = 3;

    protected $fillable = [
        'user_id',
        'equipment_id',
        'ngaymuon',
        'hantra',
        'ngaytra',
        'status',
        'description',
    ];

    /**
     * Quan hệ ngược: Phiếu mượn thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ ngược: Phiếu mượn thuộc về một thiết bị.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
