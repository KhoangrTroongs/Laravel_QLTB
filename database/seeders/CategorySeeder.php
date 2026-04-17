<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laptop', 
                'description' => 'Máy tính xách tay cho nhân viên',
                'specs' => ['RAM', 'CPU', 'Ổ cứng', 'Màn hình']
            ],
            [
                'name' => 'Monitor', 
                'description' => 'Màn hình máy tính rời',
                'specs' => ['Kích thước', 'Độ phân giải', 'Tần số quét', 'Tấm nền']
            ],
            [
                'name' => 'Phone', 
                'description' => 'Điện thoại di động công ty',
                'specs' => ['Dung lượng', 'Màu sắc', 'Pin']
            ],
            [
                'name' => 'Keyboard', 
                'description' => 'Bàn phím cơ hoặc phím văn phòng',
                'specs' => ['Kiểu kết nối', 'Switch loại', 'Layout']
            ],
            [
                'name' => 'Mouse', 
                'description' => 'Chuột không dây hoặc có dây',
                'specs' => ['DPI', 'Kiểu kết nối']
            ],
            [
                'name' => 'Headset', 
                'description' => 'Tai nghe chống ồn chuyên dụng',
                'specs' => ['Kiểu tai nghe', 'Kết nối']
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
