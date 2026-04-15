<?php

namespace Database\Factories;

use App\Models\EquipmentUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Equipment;

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
            self::$currentDate = \Carbon\Carbon::create(2025, 1, 1);
        }

        // Tăng thời gian mượn dần theo thứ tự ID (tăng từ 1-12 giờ cho mỗi bản ghi mới)
        self::$currentDate = self::$currentDate->addHours(rand(1, 12));
        $ngaymuon = self::$currentDate->copy();

        $status = $this->faker->randomElement([0, 1]);
        
        // Lấy ngẫu nhiên nhân viên đang làm việc
        $userId = User::where('status', 1)->inRandomOrder()->first()?->id ?? User::factory();

        // Xử lý chọn thiết bị dựa trên trạng thái
        if ($status == 1) {
            // Nếu là Đang mượn, tìm thiết bị đang rảnh thực sự
            $availableEquipment = Equipment::where('status', 1)
                ->whereDoesntHave('users', function ($q) {
                    $q->where('equipment_users.status', 1);
                })->inRandomOrder()->first();

            if ($availableEquipment) {
                $equipmentId = $availableEquipment->id;
            } else {
                // Nếu không còn thiết bị rảnh, ép trạng thái về 'Đã trả' để tránh lỗi logic
                $status = 0;
                $equipmentId = Equipment::inRandomOrder()->first()?->id ?? Equipment::factory();
            }
        } else {
            // Nếu là Đã trả (history), chọn đại bất kỳ thiết bị nào
            $equipmentId = Equipment::inRandomOrder()->first()?->id ?? Equipment::factory();
        }

        return [
            'user_id'     => $userId,
            'equipment_id' => $equipmentId,
            'ngaymuon'    => $ngaymuon,
            'status'      => $status,
            'description' => $this->faker->sentence(),
        ];
    }
}
