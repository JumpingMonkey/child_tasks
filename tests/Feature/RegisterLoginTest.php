<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterLoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testRegister(): void
    {
        $response = $this->createUserWithApi();
        $this->isUserCreated();

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonPath('data.name', 'test');

        $this->assertTrue(isset($response['data']['token']));
    }

    public function testLogin(): void
    {
        $this->createUserWithApi();
        $this->isUserCreated();
        
    }

    private function createUserWithApi()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'is_parent' => true,
            'password' => '12345',
            'c_password' => '12345',
        ]);

        return $response;
    }

    private function isUserCreated()
    {
        $this->assertDatabaseHas('users', ['name' => 'test', 'email' => 'test@test.com']);
    }
}
