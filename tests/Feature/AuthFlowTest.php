<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the home page containing login/register triggers is rendered.
     *
     * @return void
     */
    public function test_home_screen_with_auth_modals_can_be_rendered()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test that a user can register.
     *
     * @return void
     */
    public function test_users_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'address' => '123 Test Street',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect('/');
    }

    /**
     * Test that a user can log in with correct credentials.
     *
     * @return void
     */
    public function test_users_can_authenticate()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password', // Default password from factory
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    /**
     * Test that a user cannot log in with an invalid password.
     *
     * @return void
     */
    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
