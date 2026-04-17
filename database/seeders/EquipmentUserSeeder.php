<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EquipmentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('status', 1)->get();
        if ($users->isEmpty()) {
            return;
        }

        $equipment = Equipment::all();
        $faker = \Faker\Factory::create();

        foreach ($equipment as $item) {
            // Mỗi thiết bị tạo từ 2 đến 5 bản ghi lịch sử
            $numRecords = rand(2, 5);
            $currentDate = Carbon::now()->subMonths(4);

            for ($i = 0; $i < $numRecords; $i++) {
                // Người mượn ngẫu nhiên
                $user = $users->random();
                
                // Ngày mượn mới (cách ngày trả trước đó 1-5 ngày)
                $ngaymuon = $currentDate->copy()->addDays(rand(1, 5));
                
                // Xác định trạng thái cho bản ghi này
                // Nếu là bản ghi cuối cùng, có thể là Đang mượn (1) hoặc Đã trả (3)
                // Các bản ghi trước đó BẮT BUỘC phải là Đã trả (3) hoặc Từ chối (2)
                if ($i < $numRecords - 1) {
                    $status = $faker->randomElement([EquipmentUser::STATUS_RETURNED, EquipmentUser::STATUS_REJECTED]);
                } else {
                    // Bản ghi cuối cùng có thể đa dạng hơn
                    $status = $faker->randomElement([
                        EquipmentUser::STATUS_RETURNED, 
                        EquipmentUser::STATUS_BORROWING, 
                        EquipmentUser::STATUS_PENDING,
                        EquipmentUser::STATUS_REJECTED
                    ]);
                }

                $ngaytra = null;
                if ($status == EquipmentUser::STATUS_RETURNED) {
                    $ngaytra = $ngaymuon->copy()->addDays(rand(1, 10));
                    // Cập nhật current date cho vòng lặp tiếp theo
                    $currentDate = $ngaytra->copy();
                } elseif ($status == EquipmentUser::STATUS_REJECTED) {
                    // Nếu từ chối, current date dời lên 1 ngày để người khác mượn tiếp
                    $currentDate = $ngaymuon->copy()->addDay();
                } else {
                    // Nếu Đang mượn hoặc Chờ duyệt, không thể có bản ghi sau đó nữa
                    $currentDate = $ngaymuon->copy(); 
                    // Dừng loop cho thiết bị này vì nó đang bận
                    $this->createRecord($user->id, $item->id, $ngaymuon, $ngaytra, $status, $faker);
                    break;
                }

                $this->createRecord($user->id, $item->id, $ngaymuon, $ngaytra, $status, $faker);
            }
        }
    }

    private function createRecord($userId, $equipmentId, $ngaymuon, $ngaytra, $status, $faker)
    {
        EquipmentUser::create([
            'user_id' => $userId,
            'equipment_id' => $equipmentId,
            'ngaymuon' => $ngaymuon,
            'hantra' => $ngaymuon->copy()->addDays(14),
            'ngaytra' => $ngaytra,
            'status' => $status,
            'description' => $faker->boolean(60) ? $faker->sentence() : null,
            'created_at' => $ngaymuon,
            'updated_at' => $ngaytra ?? $ngaymuon,
        ]);
    }
}
