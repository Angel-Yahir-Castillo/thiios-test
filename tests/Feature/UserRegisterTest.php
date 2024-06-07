<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\Generator\StringManipulation\Pass\Pass;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_register() :void
    {
        //create correct data for a new user
        $data = [
            'name' => 'Example User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        //reguster user request
        $response = $this->postJson('api/register', $data);

        //validate the response
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data' => ['user']]);

        //validate registered user
        $this->assertDatabaseHas('users',['email' => 'user@example.com']);
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

        //login request
        $response = $this->postJson("api/register", $data);

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

        //login request
        $response = $this->postJson("api/register", $data);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
    }

    #[Test]
    public function email_must_be_unique(): void
    {
        //create a user
        User::factory()->create(['email' => 'user@example.com']);

        //create data with an existing email for a new user
        $data = [
            'name' => 'Example User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        //login request
        $response = $this->postJson("api/register", $data);

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

        //login request
        $response = $this->postJson("api/register", $data);

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

        //login request
        $response = $this->postJson("api/register", $data);

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

        //login request
        $response = $this->postJson("api/register", $data);

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

        //login request
        $response = $this->postJson("api/register", $data);

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

        //login request
        $response = $this->postJson("api/register", $data);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
    }

}
