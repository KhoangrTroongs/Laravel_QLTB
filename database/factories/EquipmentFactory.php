<?php

namespace Database\Factories;

use App\Models\Category;
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
        $category = Category::inRandomOrder()->first();
        $name = $this->faker->word();
        $spec = [];

        if ($category) {
            $specs_config = [
                'Laptop' => [
                    'names' => ['Dell Latitude', 'MacBook Pro', 'ThinkPad X1', 'Asus Zenbook'],
                    'RAM' => ['8GB', '16GB', '32GB', '64GB'],
                    'CPU' => ['Intel Core i5', 'Intel Core i7', 'Apple M2', 'Apple M3 Pro'],
                    'Ổ cứng' => ['256GB SSD', '512GB SSD', '1TB SSD'],
                    'Màn hình' => ['13 inch Retina', '14 inch OLED', '16 inch IPS'],
                ],
                'Monitor' => [
                    'names' => ['Dell UltraSharp', 'LG UltraGear', 'Samsung Odyssey', 'ViewSonic Elite'],
                    'Kích thước' => ['24 inch', '27 inch', '32 inch', '34 inch Curved'],
                    'Độ phân giải' => ['Full HD', '2K (QHD)', '4K (UHD)'],
                    'Tần số quét' => ['60Hz', '144Hz', '165Hz', '240Hz'],
                    'Tấm nền' => ['IPS', 'VA', 'OLED'],
                ],
                'Phone' => [
                    'names' => ['iPhone 15 Pro', 'Samsung S24 Ultra', 'Google Pixel 8', 'Xiaomi 14'],
                    'Dung lượng' => ['128GB', '256GB', '512GB', '1TB'],
                    'Màu sắc' => ['Titan Tự Nhiên', 'Đen huyền bí', 'Trắng ngọc trai', 'Xanh đại dương'],
                    'Pin' => ['4500mAh', '5000mAh', '4323mAh'],
                ],
                'Keyboard' => [
                    'names' => ['Keychron K2', 'Logitech MX Keys', 'Razer BlackWidow', 'Corsair K70'],
                    'Kiểu kết nối' => ['Bluetooth / Type-C', 'Wired Only', 'Wireless 2.4Ghz'],
                    'Switch loại' => ['Red Switch', 'Blue Switch', 'Brown Switch', 'Silent Switch'],
                    'Layout' => ['Fullsize', 'TKL (80%)', '65%', 'Compact'],
                ],
                'Mouse' => [
                    'names' => ['Logitech G502', 'Razer DeathAdder', 'SteelSeries Rival', 'MX Master 3S'],
                    'DPI' => ['8000 DPI', '16000 DPI', '25600 DPI'],
                    'Kiểu kết nối' => ['Wireless Dongle', 'Bluetooth', 'Wired USB-A'],
                ],
                'Headset' => [
                    'names' => ['Sony WH-1000XM5', 'Bose QC45', 'AirPods Max', 'SteelSeries Arctis'],
                    'Kiểu tai nghe' => ['Over-ear', 'On-ear', 'In-ear'],
                    'Kết nối' => ['Bluetooth 5.3', 'Jack 3.5mm / Bluetooth', 'Wireless Adapter'],
                ],
            ];

            $config = $specs_config[$category->name] ?? null;
            
            if ($config) {
                $name = (isset($config['names']) ? $this->faker->randomElement($config['names']) : $category->name) . ' ' . strtoupper($this->faker->bothify('?#'));
                
                // Build dynamic spec based on category's defined spec keys
                if ($category->specs) {
                    foreach ($category->specs as $key) {
                        if (isset($config[$key])) {
                            $spec[$key] = $this->faker->randomElement($config[$key]);
                        } else {
                            $spec[$key] = $this->faker->word();
                        }
                    }
                }
            } else {
                $name = $category->name . ' ' . $this->faker->word();
            }
        }

        return [
            'category_id' => $category?->id,
            'name' => $name,
            'model' => strtoupper($this->faker->bothify('??-####??')),
            'description' => 'Tình trạng: ' . $this->faker->randomElement(['Mới 100%', 'Mới 99%', 'Hàng cũ', 'Đang sử dụng tốt']) . '. ' . $this->faker->sentence(),
            'spec' => $spec,
            'status' => $this->faker->boolean(90) ? 1 : 0, 
            'available' => 1,
        ];
    }
}
