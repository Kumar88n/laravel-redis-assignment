<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostTest extends TestCase
{
    // Resets database after each test
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // Test fetching posts without authentication (should fail).
    public function test_fetch_posts_unauthenticated()
    {
        $response = $this->getJson('/api/posts');

        $response->assertStatus(401);
    }

    // Test creating a new post without authentication (should fail).
    public function test_create_post_unauthenticated()
    {
        $response = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post.'
        ]);

        $response->assertStatus(401);
    }

    // Test fetching posts with authentication
    public function test_fetch_posts_authenticated()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Post::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/posts', [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200);
    }

    // Test creating a new post with authentication
    public function test_create_post_authenticated()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post.'
        ], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post.',
            'user_id' => $user->id
        ]);
    }
}
