<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Genel Kültür',
                'description' => 'Genel kültür soruları'
            ],
            [
                'name' => 'Bilim',
                'description' => 'Bilim ve teknoloji soruları'
            ],
            [
                'name' => 'Tarih',
                'description' => 'Tarih soruları'
            ],
            [
                'name' => 'Coğrafya',
                'description' => 'Coğrafya soruları'
            ],
            [
                'name' => 'Spor',
                'description' => 'Spor soruları'
            ],
            [
                'name' => 'Sanat',
                'description' => 'Sanat ve kültür soruları'
            ],
            [
                'name' => 'Edebiyat',
                'description' => 'Edebiyat soruları'
            ],
            [
                'name' => 'Teknoloji',
                'description' => 'Teknoloji soruları'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
