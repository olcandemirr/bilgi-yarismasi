<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                'slug' => 'general',
                'description' => 'Genel bilgi soruları',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bilim',
                'slug' => 'science',
                'description' => 'Bilim ile ilgili sorular',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tarih',
                'slug' => 'history',
                'description' => 'Tarih konuları ile ilgili sorular',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Coğrafya',
                'slug' => 'geography',
                'description' => 'Coğrafya soruları',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Spor',
                'slug' => 'sports',
                'description' => 'Spor dalları ve kuralları hakkında sorular',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sanat',
                'slug' => 'art',
                'description' => 'Sanat, müzik ve kültür soruları',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
