<?php

namespace Tests\Feature;

use App\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAchievementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_get_achievements_returns_a_successful_response()
    {
        // Create User
        $password = '1234567890';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);
        // send auth to get token
        $response = $this->postJson('/tokens/create', [
            'email' => $user->email,
            'password' => $password,
        ]);
        // assert for the response 200 and the token
        $response->assertStatus(200)
            ->assertJsonStructure(['token']);

        $token = $response->json('token');
        // Send Authorization and get Achievements
        $authenticatedResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("/users/{$user->id}/achievements");

        $authenticatedResponse->assertStatus(200);
    }

    public function test_the_get_achievements_without_authentication_returns_redirect_to_login()
    {
        // Create User
        $user = User::factory()->create();
        // Request without authorization to get achievements
        $authenticatedResponse = $this->get("/users/{$user->id}/achievements");
        // assert for 302 redirect
        $authenticatedResponse->assertStatus(302);
    }
}
