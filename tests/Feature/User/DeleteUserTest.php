<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_logged_in_user_can_delete_an_existing_user(): void
    {
        //delete user request with an logged in user
        $response = $this->apiAs(User::find(1),'delete','api/users/2');

        //validate the response
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment(['message' => 'User deleted successfully']);

        //check that the user was deleted
        $this->assertDatabaseMissing('users', ['id' => 2]);
    }

    #[Test]
    public function a_non_logged_in_user_cannot_delete_a_user(): void
    {
        //delete user request without a logged in user
        $response = $this->deleteJson('api/users/2');

        //validate the response
        $response->assertStatus(401);
        $response->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    #[Test]
    public function a_logged_in_user_cannot_delete_a_non_existing_user(): void
    {
        //check that the user does not exist
        $this->assertDatabaseMissing('users', ['id' => 3]);

        //delete user request with an logged in user
        $response = $this->apiAs(User::find(1),'delete','api/users/3');

        //validate the response
        $response->assertStatus(404);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment(['message' => 'User not found']);
    }

    #[Test]
    public function an_existing_user_can_login_and_delete_an_existing_user():void
    {
        //create the credentials with an existing user
        $credentials = ['email' => 'test@example.com', 'password' => 'password'];

        //login request
        $response = $this->postJson("api/auth/login", $credentials);

        //validate the response
        $response->assertStatus(200);

        //get the token
        $access_token = $response['data']['access_token'];

        //delete user request with the bearer token
        $responseDeleted = $this->deleteJson('api/users/2', headers : [ 'Authorization' => 'Bearer ' . $access_token]);

        //validate the response
        $responseDeleted->assertStatus(200);
        $responseDeleted->assertJsonFragment(['message' => 'User deleted successfully']);

        //check that the user was deleted
        $this->assertDatabaseMissing('users', ['id' => 2]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create(['email' => 'test@example.com']);
        User::factory()->create(['email' => 'anotheruser@example.com']);
    }
}
