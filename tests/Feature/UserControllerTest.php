<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration.
     *
     * @return void
     */
    public function testRegisterUser()
    {
        // Define user data for registration
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Send a POST request to the registration route
        $response = $this->postJson('/api/register', $userData);

        // Assert that the response is successful and contains the expected message
        $response->assertStatus(201)
            ->assertJson(['message' => 'User registered successfully']);

        // Assert that the user is registered in the database
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    /**
     * Test user registration with invalid data.
     *
     * @return void
     */
    public function testRegisterUserWithInvalidData()
    {
        // Try to register a user with invalid data
        $response = $this->postJson('/api/register', []);

        // Assert that the response has the correct status code and contains validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /**
     * Test user login and token generation.
     *
     * @return void
     */
    public function testLoginUser()
    {
        // Create a user in the database
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Define login data
        $loginData = [
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        // Send a POST request to the login route
        $response = $this->postJson('/api/login', $loginData);

        // Assert that the response is successful and contains the expected data
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'token']);
    }

    /**
     * Test user login with invalid credentials.
     *
     * @return void
     */
    public function testLoginUserWithInvalidCredentials()
    {
        // Try to login with invalid credentials
        $loginData = [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ];

        // Send a POST request to the login route
        $response = $this->postJson('/api/login', $loginData);

        // Assert that the response has the correct status code and contains an error message
        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    }

    /**
     * Test user logout.
     *
     * @return void
     */
    public function testLogoutUser()
    {
        // Create a user in the database
        $user = User::factory()->create();

        // Simulate that the user is authenticated
        $this->actingAs($user);

        // Send a POST request to the logout route
        $response = $this->postJson('/api/logout');

        // Assert that the response is successful and contains a success message
        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }
}
