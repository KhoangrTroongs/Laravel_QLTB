<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\EquipmentUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EquipmentUser>
 */
class EquipmentUserFactory extends Factory
{
    private static $currentDate;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (! self::$currentDate) {
            self::$currentDate = Carbon::now()->subMonths(3);
        }

        // Tăng dần thời gian để tạo lịch sử
        self::$currentDate = self::$currentDate->addHours(rand(1, 24));
        $ngaymuon = self::$currentDate->copy();

        // Tỷ lệ trạng thái: 60% Đã trả (3), 20% Đang mượn (1), 10% Chờ duyệt (0), 10% Từ chối (2)
        $status = $this->faker->randomElement([
            3, 3, 3, 3, 3, 3, // STATUS_RETURNED
            1, 1,             // STATUS_BORROWING
            0,                // STATUS_PENDING
            2,                 // STATUS_REJECTED
        ]);

        $userId = User::where('status', 1)->inRandomOrder()->first()?->id ?? User::factory();
        $ngaytra = null;

        // Xử lý thiết bị dựa trên tính khả dụng
        if ($status == EquipmentUser::STATUS_BORROWING || $status == EquipmentUser::STATUS_PENDING) {
            // Cố gắng tìm thiết bị chưa bị ai mượn "active"
            $equipmentId = Equipment::where('status', 1)
                ->whereDoesntHave('users', function ($q) {
                    $q->whereIn('equipment_users.status', [EquipmentUser::STATUS_PENDING, EquipmentUser::STATUS_BORROWING]);
                })->inRandomOrder()->first()?->id;

            // Nếu hết thiết bị rảnh, ép về trạng thái "Đã trả"
            if (! $equipmentId) {
                $status = EquipmentUser::STATUS_RETURNED;
                $equipmentId = Equipment::inRandomOrder()->first()?->id ?? Equipment::factory();
                $ngaytra = $ngaymuon->copy()->addDays(rand(1, 7));
            }
        } else {
            // Đối với Đã trả hoặc Bị từ chối, chọn thiết bị bất kỳ
            $equipmentId = Equipment::inRandomOrder()->first()?->id ?? Equipment::factory();
            if ($status == EquipmentUser::STATUS_RETURNED) {
                $ngaytra = $ngaymuon->copy()->addDays(rand(1, 7));
            }
        }

        return [
            'user_id' => $userId,
            'equipment_id' => $equipmentId,
            'ngaymuon' => $ngaymuon,
            'hantra' => $ngaymuon->copy()->addDays(rand(7, 14)),
            'ngaytra' => $ngaytra,
            'status' => $status,
            'description' => $this->faker->boolean(70) ? $this->faker->sentence() : null,
        ];
    }
}
