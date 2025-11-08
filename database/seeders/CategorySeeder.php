<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $names = [
            'Teknologi',
            'Percintaan',
            'Sains',
            'Kesehatan',
            'Gaya Hidup',
            'Olahraga',
            'Karir',
            'Fotografi',
            'Film & TV',
            'Musik',
            'Kuliner',
            'Travel',
            'Pendidikan',
            'Ekonomi',
            'Bisnis',
            'Random',
            'Galau',
            'Lucu',
            'Bijak',
            'Lingkungan',
        ];

        foreach ($names as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => null]
            );
        }
    }
}
