<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kuliner & Makanan'],
            ['name' => 'Fashion & Busana'],
            ['name' => 'Kerajinan Tangan'],
            ['name' => 'Perabotan Rumah'],
            ['name' => 'Elektronik & Gadget'],
            ['name' => 'Kecantikan & Kesehatan'],
            ['name' => 'Jasa & Layanan'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                
            ]);
        }
    }
}