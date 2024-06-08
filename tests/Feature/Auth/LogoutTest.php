<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_authenticatedcated_user_can_logout(): void
    {
        //logout request with an authenticated user
        $response = $this->apiAs(User::find(1),'post','api/auth/logout');

        //validate the response
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment(['message' => 'Successfully logged out']);
    }

    #[Test]
    public function an_unauthenticated_user_cannot_logout(): void
    {
        //logout request with an unauthenticated useruser
        $response = $this->postJson('api/auth/logout');

        //validate the response
        $response->assertStatus(401);
        $response->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    #[Test]
    public function an_existing_user_can_login_and_logout():void
    {
        //create the credentials with an existing user
        $credentials = ['email' => 'test@example.com', 'password' => 'password'];

        //login request
        $response = $this->postJson("api/auth/login", $credentials);

        //validate the response
        $response->assertStatus(200);

        //get the token
        $access_token = $response['data']['access_token'];

        //logout request with the bearer token
        $responseLogout = $this->postJson('api/auth/logout', headers : [ 'Authorization' => 'Bearer ' . $access_token]);

        //validate the response
        $responseLogout->assertStatus(200);
        $responseLogout->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $responseLogout->assertJsonFragment(['message' => 'Successfully logged out']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
}
