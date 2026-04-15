<?php

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $devices = [
            'Laptop Dell Latitude', 'Laptop MacBook Pro', 'Laptop ThinkPad X1',
            'Cáp sạc iPhone 20W', 'Cáp sạc USB-C Samsung',
            'Màn hình Dell 24 inch', 'Màn hình LG UltraGear',
            'iPhone 15 Pro Max', 'Samsung Galaxy S24 Ultra', 'Google Pixel 8'
        ];

        return [
            'name' => $this->faker->randomElement($devices),
            'model' => strtoupper($this->faker->bothify('??-####??')),
            'description' => 'Tình trạng: ' . $this->faker->randomElement(['Mới 99%', 'Hàng cũ', 'Đang sử dụng tốt']) . '. ' . $this->faker->sentence(),
            'status' => $this->faker->randomElement([1, 1, 1, 1, 0]),
        ];
    }
}
