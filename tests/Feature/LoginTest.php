<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;

class LoginTest extends TestCase
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

    // Test to check if user can register
    public function test_user_can_register()
    {
        $random = Str::random(8);

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => $random . '@example.com',
            'password' => 'password'
        ]);
        $response->assertStatus(200);
    }

    // Test to check if user can login
    public function test_user_can_login()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(200);
    }
}
