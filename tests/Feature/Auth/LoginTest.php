<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_existing_user_can_login(): void
    {
        //create the credentials with an existing user
        $credentials = ['email' => 'test@example.com', 'password' => 'password'];

        //login request
        $response = $this->postJson("api/login", $credentials);

        //validate the response
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['access_token']]);
    }

    #[Test]
    public function a_non_existing_user_cannot_login(): void
    {
        //create the credentials with a non existing user
        $credentials = ['email' => 'test@nonexisting.com', 'password' => 'password'];

        //login request
        $response = $this->postJson("api/login", $credentials);

        //validate the response
        $response->assertStatus(401);
        $response->assertJsonFragment(['status' => 401, 'message' => 'Unauthorized']);
    }

    #[Test]
    public function an_existing_user_cannot_login_with_incorrect_password(): void
    {
        //create the credentials with a wrong password
        $credentials = ['email' => 'test@example.com', 'password' => 'incorrect'];

        //login request
        $response = $this->postJson("api/login", $credentials);

        //validate the response
        $response->assertStatus(401);
        $response->assertJsonFragment(['status' => 401, 'message' => 'Unauthorized']);
    }

    #[Test]
    public function email_must_be_required(): void
    {
        //create the credentials without email
        $credentials = ['password' => 'password'];

        //login request
        $response = $this->postJson("api/login", $credentials);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
    }

    #[Test]
    public function email_must_be_valid_email(): void
    {
        //create the credentials with a non valid email
        $credentials = ['email' => 'email', 'password' => 'password'];

        //login request
        $response = $this->postJson("api/login", $credentials);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
    }

    #[Test]
    public function password_must_be_required(): void
    {
        //create the credentials without password
        $credentials = ['email' => 'test@example.com',];

        //login request
        $response = $this->postJson("api/login", $credentials);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
}
