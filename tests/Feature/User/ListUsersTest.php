<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_logged_in_user_can_get_all_users(): void
    {
        //user list request with an logged in user
        $response = $this->apiAs(User::find(1),'get','api/users/');

        //validate the response
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data' => ['users'], 'status', 'errors']);
        $response->assertJsonStructure(['data' => ['users', 'total', 'count', 'per_page', 'current_page', 'total_pages']]);
    }

    #[Test]
    public function a_non_logged_in_user_cannot_get_the_users_list(): void
    {
        //user list request without a logged in user
        $response = $this->getJson('api/users/');

        //validate the response
        $response->assertStatus(401);
        $response->assertJsonFragment(['message' => 'Unauthenticated.']);
    }
    #[Test]
    public function an_existing_user_can_login_and_get_all_users():void
    {
        //create the credentials with an existing user
        $credentials = ['email' => 'test@example.com', 'password' => 'password'];

        //login request
        $response = $this->postJson("api/auth/login", $credentials);

        //validate the response
        $response->assertStatus(200);

        //get the token
        $access_token = $response['data']['access_token'];

        //user list request with the bearer token
        $responseUsers = $this->getJson('api/users/', headers : [ 'Authorization' => 'Bearer ' . $access_token]);

        //validate the response
        $responseUsers->assertStatus(200);
        $responseUsers->assertJsonStructure(['message', 'data' => ['users'], 'status', 'errors']);
        $responseUsers->assertJsonStructure(['data' => ['users', 'total', 'count', 'per_page', 'current_page', 'total_pages']]);
    }


    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create(['email' => 'test@example.com']);
        User::factory()->create(['email' => 'anotheruser@example.com']);
    }
}
