<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Chưa phân loại',
            ],
            [
                'name' => 'Ảnh vui',
            ],
            [
                'name' => 'Động vật',
            ],
            [
                'name' => 'Truyện',
            ],
            [
                'name' => 'Hoạt hình',
            ],
            [
                'name' => 'Gia đình',
            ],
            [
                'name' => 'Hài hước',
            ],
            [
                'name' => 'Xe',
            ],
            [
                'name' => 'Đồ thủ công',
            ],
            [
                'name' => 'Cây hoa',
            ],
            [
                'name' => 'Người đẹp',
            ],
            [
                'name' => 'Hỏi',
            ],
            [
                'name' => 'Tin tức',
            ],
            [
                'name' => 'Khoa học',
            ],
            [
                'name' => 'Lịch sử',
            ]
        ];

        foreach ($categories as $category) {
            App\Category::create($category);
        }
    }
}
