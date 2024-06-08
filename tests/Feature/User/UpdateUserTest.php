<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Traits\ReflectsClosures;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_authenticated_user_can_update_an_existing_user(): void
    {
        //data to update
        $data = [
            'name' => 'New Name',
        ];

        //update user request with an authenticated user
        $response = $this->apiAs(User::find(1),'put','api/users/2',$data);

        //validate the response
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment(['message' => 'User updated successfully']);

        //check the user was updated successfully
        $this->assertDatabaseHas('users',['id' => 2, 'name' => 'New Name']);
    }

    #[Test]
    public function an_unauthenticated_user_cannot_update_an_existing_user(): void
    {
        //data to update
        $data = [
            'name' => 'New Name',
        ];

        //update user request with an authenticated user
        $response = $this->putJson('api/users/2',$data);

        //validate the response
        $response->assertStatus(401);
        $response->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    #[Test]
    public function cannot_update_email_field(): void
    {
        //data to update
        $data = [
            'name' => 'New Name',
            'email' => 'newmail@example.com',
        ];

        //validate the user to update
        $this->assertDatabaseHas('users',['id' => 2, 'name' => 'Example Name', 'email' => 'anotheruser@example.com']);

        //update user request with an authenticated user
        $response = $this->apiAs(User::find(1),'put','api/users/2',$data);

        //validate the response
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment(['message' => 'User updated successfully']);

        //check the user was updated successfully
        $this->assertDatabaseHas('users',['id' => 2, 'name' => 'New Name', 'email' => 'anotheruser@example.com']);
    }

    #[Test]
    public function cannot_update_a_non_existing_user(): void
    {
        //check that the user does not exist
        $this->assertDatabaseMissing('users', ['id' => 3]);

        //data to update
        $data = [
            'name' => 'New Name',
        ];

        //update user request with an authenticated user
        $response = $this->apiAs(User::find(1),'put','api/users/3',$data);

        //validate the response
        $response->assertStatus(404);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors']);
        $response->assertJsonFragment(['message' => 'User not found']);
    }

    #[Test]
    public function name_must_be_string(): void
    {
        //data to update
        $data = [
            'name' => 123456,
        ];

        //validate the user to update
        $this->assertDatabaseHas('users',['id' => 2, 'name' => 'Example Name']);

        //update user request with an authenticated user
        $response = $this->apiAs(User::find(1),'put','api/users/2',$data);

        //validate the response
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);

        //updated nothing
        $this->assertDatabaseHas('users',['id' => 2, 'name' => 'Example Name']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create(['email' => 'test@example.com']);
        User::factory()->create(['name' => 'Example Name','email' => 'anotheruser@example.com']);
    }
}
