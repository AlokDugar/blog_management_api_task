<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Permission::create(['name' => 'post-create']);
        Permission::create(['name' => 'post-edit']);
        Permission::create(['name' => 'post-delete']);

        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo(['post-create', 'post-edit']);
    }

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $category = Category::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'body' => 'This is a test post body.',
            'category_id' => $category->id,
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'post' => [
                        'id',
                        'title',
                        'body',
                        'author_name',
                        'category_name'
                    ]
                ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'author_id' => $user->id,
        ]);
    }

    public function test_user_can_only_edit_own_posts(): void
    {
        $author = User::factory()->create();
        $author->assignRole('editor');
        $otherUser = User::factory()->create();
        $otherUser->assignRole('editor');

        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'author_id' => $author->id,
            'category_id' => $category->id,
        ]);

        Sanctum::actingAs($otherUser);

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => 'Updated Title',
            'body' => 'Updated body',
            'category_id' => $category->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_public_posts_endpoint_works(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Post::factory()->count(5)->create([
            'author_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $response = $this->getJson('/api/public/posts');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'title',
                            'author_name',
                            'category_name',
                            'created_at'
                        ]
                    ]
                ]);
    }
}
