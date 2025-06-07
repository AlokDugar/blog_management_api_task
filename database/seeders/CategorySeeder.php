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
            'Technology',
            'Health',
            'Travel',
            'Food',
            'Sports',
            'Entertainment',
            'Business',
            'Science',
            'Education',
            'Lifestyle',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }

        // Create additional categories for large data testing
        for ($i = 1; $i <= 1000; $i++) {
            Category::create([
                'name' => "Category {$i}",
                'slug' => "category-{$i}",
            ]);
        }
    }
}
