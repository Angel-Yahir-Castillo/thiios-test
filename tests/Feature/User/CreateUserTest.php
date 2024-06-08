<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_logged_in_user_can_register_a_new_user() :void
    {
        //create correct data for a new user
        $data = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        //register a new user request with an logged in user
        $response = $this->apiAs(User::find(1),'post','api/users/', $data);

        //validate the response
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data' => ['user']]);

        //validate registered user
        $this->assertDatabaseHas('users',['email' => 'newuser@example.com']);
    }

    #[Test]
    public function a_non_logged_in_user_cannot_register_a_new_user(): void
    {
        //create correct data for a new user
        $data = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        //register a new user request without a logged in user
        $response = $this->postJson('api/users/', $data);

        //validate the response
        $response->assertStatus(401);
        $response->assertJsonFragment(['message' => 'Unauthenticated.']);

        //check that the user does not exist
        $this->assertDatabaseMissing('users', ['email' => 'newuser@example.com']);
    }

    #[Test]
    public function email_must_be_required(): void
    {
        //create data without email for a new user
        $data = [
            'name' => 'Example User',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        //register a new user request with an logged in user
        $response = $this->apiAs(User::find(1),'post','api/users/', $data);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
    }

    #[Test]
    public function email_must_be_valid_email(): void
    {
        //create data with a wrong email format for a new user
        $data = [
            'name' => 'Example User',
            'email' => 'email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        //register a new user request with an logged in user
        $response = $this->apiAs(User::find(1),'post','api/users/', $data);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
    }

    #[Test]
    public function email_must_be_unique(): void
    {
        //create data with an existing email for a new user
        $data = [
            'name' => 'Example User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        //register a new user request with an logged in user
        $response = $this->apiAs(User::find(1),'post','api/users/', $data);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
    }

    #[Test]
    public function password_must_be_required(): void
    {
        //create data without password for a new user
        $data = [
            'name' => 'Example User',
            'email' => 'user@example.com',
            'password_confirmation' => 'password',
        ];

        //register a new user request with an logged in user
        $response = $this->apiAs(User::find(1),'post','api/users/', $data);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
    }

    #[Test]
    public function password_must_have_at_lease_8_characters(): void
    {
        //create data with a password with length less than 8 for a new user
        $data = [
            'name' => 'Example User',
            'email' => 'user@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ];

        //register a new user request with an logged in user
        $response = $this->apiAs(User::find(1),'post','api/users/', $data);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
    }

    #[Test]
    public function password_must_be_confirmed(): void
    {
        //create data without a password confirmation for a new user
        $data = [
            'name' => 'Example User',
            'email' => 'user@example.com',
            'password' => 'password',
        ];

        //register a new user request with an logged in user
        $response = $this->apiAs(User::find(1),'post','api/users/', $data);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
    }

    #[Test]
    public function name_must_be_required(): void
    {
        //create data without name for a new user
        $data = [
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        //register a new user request with an logged in user
        $response = $this->apiAs(User::find(1),'post','api/users/', $data);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
    }

    #[Test]
    public function name_must_be_a_string(): void
    {
        //create data without name for a new user
        $data = [
            'name' => 1234,
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        //register a new user request with an logged in user
        $response = $this->apiAs(User::find(1),'post','api/users/', $data);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create(['email' => 'test@example.com']);
    }
}
