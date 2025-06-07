<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        // Create 1000+ posts for large data testing
        for ($i = 1; $i <= 1500; $i++) {
            Post::create([
                'title' => "Post Title {$i}",
                'body' => "This is the body content for post {$i}. Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                'category_id' => $categories->random()->id,
                'author_id' => $users->random()->id,
            ]);
        }
    }
}
