<?php

namespace Tests\Feature;

use App\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_create_token_successful_response()
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
    }

    public function test_the_create_token_unauthorized_response()
    {
        // send invalid auth
        $response = $this->postJson('/tokens/create', [
            'email' => 'invalid@email',
            'password' => 'Not a valid password',
        ]);
        // assert for the unauthorized response
        $response->assertStatus(403);
    }
}
